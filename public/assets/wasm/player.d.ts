/** Exported memory */
export declare const memory: WebAssembly.Memory;
// Exported runtime interface
export declare function __new(size: number, id: number): number;
export declare function __pin(ptr: number): number;
export declare function __unpin(ptr: number): void;
export declare function __collect(): void;
export declare const __rtti_base: number;
/**
 * assembly/index/xorDecrypt
 * @param encodedData `~lib/string/String`
 * @param key `~lib/string/String`
 * @returns `~lib/string/String`
 */
export declare function xorDecrypt(encodedData: string, key: string): string;
/**
 * assembly/index/hexToString
 * @param hex `~lib/string/String`
 * @returns `~lib/string/String`
 */
export declare function hexToString(hex: string): string;
/**
 * assembly/index/base64Decode
 * @param input `~lib/string/String`
 * @returns `~lib/string/String`
 */
export declare function base64Decode(input: string): string;
/**
 * assembly/index/decodePlayerConfig
 * @param encodedBlob `~lib/string/String`
 * @returns `~lib/string/String`
 */
export declare function decodePlayerConfig(encodedBlob: string): string;
/**
 * assembly/index/getResultLength
 * @returns `i32`
 */
export declare function getResultLength(): number;
