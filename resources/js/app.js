import "./bootstrap";

// import Alpine from "@alpinejs/csp";
import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

if (import.meta.env.MODE === "production") {
    ["log", "warn", "error", "info", "debug"].forEach((method) => {
        console[method] = function () {};
    });
}
