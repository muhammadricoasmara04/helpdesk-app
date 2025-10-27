import axios from "axios";

document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("loginForm");
    const baseUrl =
        import.meta.env.VITE_API_BASE_URL || "http://127.0.0.1:8000/api";

    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        const email = form.email.value;
        const password = form.password.value;
        const csrfToken = document.querySelector('input[name="_token"]')?.value;

        try {
            // Kirim request login ke API
            const response = await axios.post(`${baseUrl}/login`, {
                email,
                password,
                _token: csrfToken,
            });
            console.log(response)
            // Pastikan respons berisi token
            const token = response.data?.token;
            if (!token) {
                alert("Login gagal: token tidak ditemukan di respons API.");
                return;
            }
            const role = response.data.user.role_id === 1 ? "admin" : "user";
            // Simpan token ke localStorage
            localStorage.setItem("token", token);

            alert("Login berhasil!");

            if (role === "admin") {
                window.location.href = "/dashboard";
            } else {
                window.location.href = "/dashboard/user";
            }
        } catch (error) {
            console.error("Login error:", error);
            alert(error.response?.data?.message || "Login gagal!");
        }
    });
});
