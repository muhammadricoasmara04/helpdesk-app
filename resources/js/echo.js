import Echo from "laravel-echo";

import Pusher from "pusher-js";
window.Pusher = Pusher;
const token = localStorage.getItem("token") || sessionStorage.getItem("token");
console.log("Using token:", token);
console.log(
    "Broadcast Auth URL:",
    "http://127.0.0.1:8000/api/broadcasting/auth"
);
console.log("Bearer Token:", localStorage.getItem("token"));
window.Echo = new Echo({
    broadcaster: "reverb",
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST ?? window.location.hostname,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
    forceTLS: false,
    enabledTransports: ["ws", "wss"],
    authEndpoint: "http://127.0.0.1:8000/api/broadcasting/auth",
    auth: {
        headers: {
            Authorization: `Bearer ${token}`,
            Accept: "application/json",
        },
    },
});
