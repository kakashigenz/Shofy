function setCookie(name, value, day) {
    let expires = "";
    if (day) {
        const time = new Date();
        time.setDate(time.getDate() + day);
        expires = `; expires=${time.toUTCString()}`;
    }
    document.cookie = `${name}=${value}${expires}+; path=/; SameSite=Lax`;
}
function getCookie(name) {
    const cookies = document.cookie.split(";");
    const cookie = cookies.find((item) => item.trim().startsWith(name));
    const start = cookie.indexOf("=");
    return cookie.substring(start + 1);
}
export { setCookie, getCookie };
