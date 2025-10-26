import axios from "axios";

document.addEventListener("DOMContentLoaded", () => {
    getDataApplicationProblems();
    loadApplications();
});

async function getDataApplicationProblems() {
    const baseUrl = import.meta.env.VITE_API_BASE_URL;
    const tableBody = document.getElementById("problems-table-body");
    if (!tableBody) return;

    const token = localStorage.getItem("token");
    if (!token) return;

    try {
        const [problemsRes, appsRes] = await Promise.all([
            axios.get(`${baseUrl}/application-problems`, {
                headers: { Authorization: `Bearer ${token}` },
            }),
            axios.get(`${baseUrl}/applications`, {
                headers: { Authorization: `Bearer ${token}` },
            }),
        ]);

        const problems = problemsRes.data.data || [];
        const applications = appsRes.data.data || [];

        tableBody.innerHTML = "";

        problems.forEach((problem) => {
            const app = applications.find(
                (a) => a.id === problem.application_id
            );
            const appName = app?.application_name || "-";

            const row = document.createElement("tr");
            row.innerHTML = `
                <td class="border px-4 py-2">${problem.problem_name || "-"}</td>
                <td class="border px-4 py-2">${problem.description || "-"}</td>
                <td class="border px-4 py-2">${appName}</td>
                <td class="border px-4 py-2">${
                    problem.application_id || "-"
                }</td>
                <td class="border px-4 py-2">${problem.created_id || "-"}</td>
                <td class="border px-4 py-2">${formatDate(
                    problem.created_at
                )}</td>
                <td class="border px-4 py-2 text-center space-x-2">
                   <a href="/dashboard/application-problem/${problem.id}"
                    class="bg-green-500 text-white px-3 py-1 rounded-md hover:bg-green-600 transition">
                    Show
                    </a>
                    <button class="bg-red-500 text-white px-3 py-1 rounded-md hover:bg-red-600 transition delete-btn"
                            data-id="${problem.id}">
                        Delete
                    </button>
                </td>
            `;
            tableBody.appendChild(row);
        });
    } catch (error) {
        console.error(error);
    }
}

function formatDate(dateString) {
    if (!dateString) return "-";
    const date = new Date(dateString);
    return date.toLocaleString("id-ID", {
        day: "2-digit",
        month: "short",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
}

async function loadApplications() {
    const baseUrl = import.meta.env.VITE_API_BASE_URL;
    const token = localStorage.getItem("token");
    const selectApp = document.getElementById("application_id");

    if (!selectApp) return;

    if (!token) {
        selectApp.innerHTML = `<option value="">Silakan login terlebih dahulu</option>`;
        return;
    }

    try {
        const response = await axios.get(`${baseUrl}/applications`, {
            headers: { Authorization: `Bearer ${token}` },
        });

        const applications = response.data.data || [];

        selectApp.innerHTML = `<option value="">Pilih Aplikasi...</option>`;
        applications.forEach((app) => {
            const option = document.createElement("option");
            option.value = app.id;
            option.textContent = app.application_name;
            selectApp.appendChild(option);
        });
    } catch (error) {
        console.error("Gagal memuat aplikasi:", error);
        selectApp.innerHTML = `<option value="">Gagal memuat data aplikasi</option>`;
    }
}

document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("applicationProblemForm");
    if (form) {
        form.addEventListener("submit", storeApplicationProblem);
    }
});

async function storeApplicationProblem(e) {
    e.preventDefault();

    const baseUrl = import.meta.env.VITE_API_BASE_URL;
    const token = localStorage.getItem("token");
    const messageEl = document.getElementById("responseMessage");

    if (!token) {
        messageEl.textContent = "Belum login. Silakan login terlebih dahulu.";
        messageEl.className = "text-red-500";
        return;
    }

    const formData = {
        problem_name: document.getElementById("problem_name").value.trim(),
        description: document.getElementById("description").value.trim(),
        application_id: document.getElementById("application_id").value.trim(),
        created_id: crypto.randomUUID(),
        updated_id: crypto.randomUUID(),
    };

    if (
        !formData.problem_name ||
        !formData.description ||
        !formData.application_id
    ) {
        messageEl.textContent = "Semua field wajib diisi.";
        messageEl.className = "text-red-500";
        return;
    }

    try {
        messageEl.textContent = "Menyimpan data...";
        messageEl.className = "text-gray-500";

        const response = await axios.post(
            `${baseUrl}/application-problems`,
            formData,
            {
                headers: { Authorization: `Bearer ${token}` },
            }
        );

        if (response.data.success) {
            messageEl.textContent = "Problem aplikasi berhasil ditambahkan!";
            messageEl.className = "text-green-600";
            e.target.reset();
        } else {
            messageEl.textContent = "Gagal menambahkan problem aplikasi.";
            messageEl.className = "text-red-500";
        }
    } catch (error) {
        console.error("Error saat menambahkan problem aplikasi:", error);
        messageEl.textContent =
            error.response?.status === 401
                ? "Sesi login berakhir. Silakan login ulang."
                : `Gagal menyimpan: ${error.message}`;
        messageEl.className = "text-red-500";
    }
}
