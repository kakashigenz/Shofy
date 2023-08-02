export default function debounce(callback, time, ...param) {
    if (callback) {
        return setTimeout(() => {
            callback(...param);
        }, time);
    }
}
