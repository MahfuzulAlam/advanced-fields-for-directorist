(function() {
    const el = document.getElementById("qrcode");
    const text = el.getAttribute("text") || "https://example.com";
    const width = parseInt(el.getAttribute("width")) || 256;
    const height = parseInt(el.getAttribute("height")) || 256;

    new QRCode(el, {
        text: text,
        width: width,
        height: height,
        colorDark : "#000000",
        colorLight : "#ffffff",
        correctLevel : QRCode.CorrectLevel.H
    });
})();