<?php

namespace App\Helpers;

/**
 * PlayerEncoder - Encrypts player configuration AND code for WASM-style protection
 * Everything appears as binary in page source
 */
class PlayerEncoder
{
    /**
     * Encode player configuration to protected format
     */
    public static function encode(array $config): string
    {
        $json = json_encode($config);
        $key = self::generateKey();
        $encoded = self::xorEncode($json, $key);
        $b64 = base64_encode($encoded);
        $hex = bin2hex($b64);
        $encodedKey = bin2hex(base64_encode($key));
        return $encodedKey . '.' . $hex;
    }

    /**
     * Encode entire JavaScript code + config into a single encrypted blob
     */
    public static function encodeFullPlayer(array $config, array $settings, array $adConfig = []): string
    {
        // Create the full player initialization script
        $script = self::generatePlayerScript($config, $settings, $adConfig);

        // Encode the script
        $key = self::generateKey();
        $encoded = self::xorEncode($script, $key);
        $b64 = base64_encode($encoded);
        $hex = bin2hex($b64);
        $encodedKey = bin2hex(base64_encode($key));

        return $encodedKey . '.' . $hex;
    }

    /**
     * Encode any JavaScript code into encrypted blob
     */
    public static function encodeScript(string $script): string
    {
        if (empty($script)) {
            return '';
        }

        $key = self::generateKey();
        $encoded = self::xorEncode($script, $key);
        $b64 = base64_encode($encoded);
        $hex = bin2hex($b64);
        $encodedKey = bin2hex(base64_encode($key));

        return $encodedKey . '.' . $hex;
    }

    /**
     * Generate the complete player initialization script with optional ads
     */
    private static function generatePlayerScript(array $config, array $settings, array $adConfig = []): string
    {
        $sources = json_encode($config['sources']);
        $aboutlink = $config['aboutlink'];
        $sharelink = $config['sharelink'];

        $skinCode = ($settings['netflix_skin'] ?? true) ? 'c.skin={name:"netflix"};' : '';
        $shareCode = ($settings['share_button'] ?? true) ?
            'c.sharing={sites:["facebook","twitter","email","linkedin"],code:\'<iframe src="' . $sharelink . '" width="640" height="360" frameborder="0" allowfullscreen></iframe>\',link:"' . $sharelink . '"};' : '';
        $rateCode = ($settings['playback_rate'] ?? true) ? 'c.playbackRateControls=true;' : '';

        // Ad interception code
        $adCode = '';
        if (!empty($adConfig['enabled']) && !empty($adConfig['links']) && $adConfig['clicks'] > 0) {
            $clicks = $adConfig['clicks'];
            $links = json_encode($adConfig['links']);

            $adCode = <<<ADJS
var _c=0,_r=$clicks,_l=$links,_a=false;
p.on('beforePlay',function(){if(_c<_r){p.stop();window.open(_l[_c%_l.length],'_blank');_c++;if(_c>=_r)_a=true;}});
document.addEventListener('visibilitychange',function(){if(!document.hidden&&_a){_a=false;p.play();}});
ADJS;
        }

        // Get anti-adblock code
        $antiAdblock = self::getAntiAdblockScript();

        return <<<JS
$antiAdblock
(function(){
// Script block reveal - hide warning, show player
var sw=document.getElementById('sb-warn'),sp=document.getElementById('player');
if(sw)sw.style.display='none';if(sp)sp.style.display='';
// Sandbox detection
try{if(window.self!==window.top){var tt=window.top;try{var _=tt.location.href}catch(e){if(window.frameElement&&window.frameElement.hasAttribute('sandbox')){if(sw){sw.style.display='flex';sw.querySelector('.sb-title').textContent='Sandbox Restricted';sw.querySelector('.sb-text').textContent='Remove iframe sandbox restrictions to watch.';}if(sp)sp.style.display='none';return;}}}}catch(e){}
if(window._vsBlocked)return;
var c={controls:true,autostart:false,displaytitle:true,displaydescription:true,abouttext:"VidStreaming",aboutlink:"$aboutlink",stretching:"uniform",captions:{color:"#FFF",fontSize:14,backgroundOpacity:0,edgeStyle:"raised"},playlist:[{sources:$sources}]};
$skinCode
$shareCode
$rateCode
var p=jwplayer("player").setup(c);
p.on("ready",function(){var n=p.getContainer(),b=n.querySelector(".jw-controlbar"),t=n.querySelector(".jw-slider-time");if(b&&t){t.classList.add("jw-slider-top");b.insertBefore(t,b.firstChild)}var r=n.querySelector(".jw-display-icon-rewind");if(r){var f=r.cloneNode(true),d=f.querySelector(".jw-icon-rewind");if(d){d.style.transform="scaleX(-1)";d.ariaLabel="Forward 10 Seconds"}var x=n.querySelector(".jw-display-icon-next");if(x)x.parentNode.insertBefore(f,x);var bc=n.querySelector(".jw-button-container"),rb=bc?bc.querySelector(".jw-icon-rewind"):null;if(rb){var fb=rb.cloneNode(true);fb.style.transform="scaleX(-1)";fb.ariaLabel="Forward 10 Seconds";rb.parentNode.insertBefore(fb,rb.nextElementSibling);[d,fb].forEach(function(b){if(b)b.onclick=function(){p.seek(p.getPosition()+10)}})}}$adCode});
})();
JS;
    }

    /**
     * Get the anti-adblock detection script
     */
    private static function getAntiAdblockScript(): string
    {
        return <<<'ADBJS'
(function(){window._vsReady=false;window._vsBlocked=false;var C={i:2000,d:{b:'_vsc_b',s:'_vsc_s',p:'_vsc_m_'},c:{b:'adsense adsbygoogle ad-banner ad-unit advertisement',s:'adsbygoogle'},t:{b:'display:block!important;visibility:visible!important;opacity:1!important;height:1px!important;width:1px!important;position:absolute!important;left:-10000px!important;top:-10000px!important;pointer-events:none!important;'}},S={w:false,e:{b:null,s:null}};function cb(){S.e.b=document.getElementById(C.d.b);if(!S.e.b){S.e.b=document.createElement('div');S.e.b.id=C.d.b;S.e.b.className=C.c.b;S.e.b.style.cssText=C.t.b;document.body.appendChild(S.e.b);}S.e.s=document.getElementById(C.d.s);if(!S.e.s){S.e.s=document.createElement('ins');S.e.s.id=C.d.s;S.e.s.className=C.c.s;S.e.s.style.cssText=C.t.b;document.body.appendChild(S.e.s);}}function rb(){if(S.w)return;['b','s'].forEach(function(k){var e=S.e[k];if(e&&e.isConnected){document.body.appendChild(e);}else{cb();}});}function gi(){return C.d.p+Math.random().toString(36).substr(2,9);}function cm(){if(S.w)return;window._vsBlocked=true;var pc=document.getElementById('player');if(pc)pc.style.display='none';var ex=document.querySelector('[id^="'+C.d.p+'"]');if(ex){rs(ex);S.w=true;return;}var mid=gi();var sty=document.createElement('style');sty.textContent='@keyframes _vswave{0%{transform:scale(0.8);opacity:0.8}100%{transform:scale(2.5);opacity:0}}@keyframes _vsbounce{0%,100%{transform:translateY(0)}50%{transform:translateY(-5px)}}@keyframes _vsf{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:translateY(0)}}@keyframes _vsglow{0%,100%{box-shadow:0 4px 20px rgba(220,38,38,0.4)}50%{box-shadow:0 4px 30px rgba(220,38,38,0.6)}}@keyframes _vsspin{from{transform:rotate(0deg)}to{transform:rotate(360deg)}}';document.head.appendChild(sty);var m=document.createElement('div');m.id=mid;m.style.cssText='position:fixed;inset:0;background:#0a0a0a;display:flex;flex-direction:column;align-items:center;justify-content:center;z-index:99999;font-family:-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,sans-serif;padding:1.5rem;box-sizing:border-box;';var ico=document.createElement('div');ico.style.cssText='width:70px;height:70px;margin-bottom:1.25rem;position:relative;display:flex;align-items:center;justify-content:center;';for(var w=0;w<3;w++){var wave=document.createElement('div');wave.style.cssText='position:absolute;width:100%;height:100%;border:2px solid rgba(220,38,38,0.4);border-radius:50%;animation:_vswave 2s ease-out infinite;animation-delay:'+w*0.6+'s;';ico.appendChild(wave);}var warn=document.createElement('div');warn.style.cssText='position:relative;animation:_vsbounce 2s ease-in-out infinite;z-index:1;';warn.innerHTML='<svg width="36" height="36" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" fill="#dc2626" stroke="#dc2626" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><line x1="12" y1="9" x2="12" y2="13" stroke="#fff" stroke-width="2" stroke-linecap="round"/><circle cx="12" cy="17" r="1" fill="#fff"/></svg>';ico.appendChild(warn);m.appendChild(ico);var ti=document.createElement('div');ti.style.cssText='font-size:1.3rem;font-weight:600;color:#fff;margin-bottom:0.4rem;animation:_vsf 0.4s ease 0.1s backwards;';ti.textContent='AdBlock Detected';m.appendChild(ti);var st=document.createElement('div');st.style.cssText='font-size:0.85rem;color:rgba(255,255,255,0.5);margin-bottom:1.5rem;text-align:center;max-width:380px;line-height:1.5;animation:_vsf 0.4s ease 0.15s backwards;';st.textContent='Please disable your ad blocker to continue watching. Our ads help keep this service free.';m.appendChild(st);var bt=document.createElement('button');bt.style.cssText='padding:0.75rem 1.5rem;background:#dc2626;color:#fff;border:none;border-radius:8px;font-size:0.875rem;font-weight:600;cursor:pointer;font-family:inherit;transition:all 0.2s ease;display:flex;align-items:center;gap:0.5rem;animation:_vsf 0.4s ease 0.2s backwards,_vsglow 2s ease-in-out infinite 0.4s;';bt.innerHTML='<svg class="_ico" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg><span>Refresh Page</span>';var icoEl=bt.querySelector('._ico');bt.onmouseover=function(){bt.style.transform='translateY(-2px)';bt.style.background='#b91c1c';};bt.onmouseout=function(){bt.style.transform='translateY(0)';bt.style.background='#dc2626';};bt.onclick=function(){if(icoEl)icoEl.style.animation='_vsspin 0.4s linear infinite';bt.querySelector('span').textContent='Refreshing...';setTimeout(function(){location.reload();},300);};m.appendChild(bt);document.body.appendChild(m);S.w=true;}function rs(m){m.id=gi();m.style.cssText='position:fixed;inset:0;background:#0a0a0a;display:flex;flex-direction:column;align-items:center;justify-content:center;z-index:99999;padding:1.5rem;box-sizing:border-box;';m.style.clipPath='none';}function ib(e){if(!e)return true;if(e.offsetHeight===0||e.offsetWidth===0)return true;var s=getComputedStyle(e);return s.display==='none'||s.visibility==='hidden'||s.opacity==='0';}function cs(){if(S.w){var m=document.querySelector('[id^="'+C.d.p+'"]');if(m){rs(m);}else{S.w=false;cm();}return;}cb();var d=false;if(ib(S.e.b))d=true;var al=document.querySelectorAll('ins.'+C.c.s);for(var i=0;i<al.length;i++){var a=al[i];var cn=Array.from(a.classList);var hs=cn.some(function(c){return c!=='adsbygoogle'&&!c.startsWith('adsbygoogle-');});if(hs||a.hasAttribute('title')){d=true;break;}}if(d){cm();}else{window._vsReady=true;}}function ri(){cs();[100,500,1000,2000].forEach(function(d){setTimeout(cs,d);});}function sl(){var cy=0,AL=30;setInterval(function(){cs();if(!S.w&&cy<AL){rb();cy++;}},C.i);document.addEventListener('visibilitychange',function(){if(!document.hidden){cs();cy=0;}});}function be(){window.addEventListener('pageshow',function(e){if(e.persisted)cs();});window.addEventListener('load',function(){setTimeout(cs,100);});}function im(){var R=[/height\s*:\s*1px\s*!important/i,/width\s*:\s*1px\s*!important/i];var id=false;function ck(){if(id)return;var ifs=document.querySelectorAll('ins iframe[id^="aswift_"]');for(var i=0;i<ifs.length;i++){var st=ifs[i].getAttribute('style');if(!st)continue;var ct=0;for(var j=0;j<R.length;j++){if(R[j].test(st))ct++;}if(ct>=2){id=true;cm();break;}}}var ob=new MutationObserver(function(ms){var sc=false;for(var i=0;i<ms.length;i++){var m=ms[i];if(m.type==='childList')sc=true;else if(m.type==='attributes'&&(m.target.tagName==='IFRAME'||m.target.tagName==='INS'))sc=true;}if(sc)ck();});ob.observe(document.documentElement,{childList:true,subtree:true,attributes:true,attributeFilter:['style']});setInterval(ck,3500);}if(document.readyState==='loading'){document.addEventListener('DOMContentLoaded',function(){ri();sl();be();im();});}else{ri();sl();be();im();}})();
ADBJS;
    }

    private static function generateKey(): string
    {
        return bin2hex(random_bytes(16));
    }

    private static function xorEncode(string $data, string $key): string
    {
        $result = '';
        $keyLen = strlen($key);
        for ($i = 0; $i < strlen($data); $i++) {
            $result .= chr(ord($data[$i]) ^ ord($key[$i % $keyLen]));
        }
        return $result;
    }
}
