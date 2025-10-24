import axios from "axios";

document.addEventListener("DOMContentLoaded", () => {
    getDataApplicationProblems();
});

async function getDataApplicationProblems() {
    const baseUrl = import.meta.env.VITE_API_BASE_URL;
    const tableBody = document.getElementById("problems-table-body");
    if (tableBody) {
        getDataApplicationProblems(tableBody);
    }
    if (!tableBody) return;
    const setMessageRow = (message, color = "text-gray-500") => {
        tableBody.innerHTML = `
            <tr>
                <td colspan="5" class="text-center py-4 ${color}">${message}</td>
            </tr>
        `;
    };

    try {
        const token = localStorage.getItem("token");

        if (!token) {
            setMessageRow(
                "Belum login. Silakan login terlebih dahulu.",
                "text-red-500"
            );
            return;
        }

        const response = await axios.get(`${baseUrl}/application-problems`, {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        });

        const problems = response.data?.data || [];

        if (response.data.success && problems.length > 0) {
            tableBody.innerHTML = "";

            problems.forEach((problem) => {
                const row = document.createElement("tr");
                row.innerHTML = `
                    <td class="border px-4 py-2">${
                        problem.problem_name || "-"
                    }</td>
                    <td class="border px-4 py-2">${
                        problem.description || "-"
                    }</td>
                    <td class="border px-4 py-2">${
                        problem.application_id || "-"
                    }</td>
                    <td class="border px-4 py-2">${
                        problem.created_id || "-"
                    }</td>
                    <td class="border px-4 py-2">${formatDate(
                        problem.created_at
                    )}</td>
                `;
                tableBody.appendChild(row);
            });
        } else {
            setMessageRow("Tidak ada data problem aplikasi tersedia.");
        }
    } catch (error) {
        console.error("Error saat memuat application problems:", error);

        if (error.response && error.response.status === 401) {
            setMessageRow(
                "Sesi login berakhir. Silakan login ulang.",
                "text-red-500"
            );
        } else {
            setMessageRow(
                `Gagal memuat data problem aplikasi: ${error.message}`,
                "text-red-500"
            );
        }
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

    // Ambil data dari form
    const formData = {
        problem_name: document.getElementById("problem_name").value.trim(),
        description: document.getElementById("description").value.trim(),
        application_id: document.getElementById("application_id").value.trim(),
        created_id: crypto.randomUUID(),
        updated_id: crypto.randomUUID(),
    };

    // Validasi sederhana
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
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            }
        );

        if (response.data.success) {
            messageEl.textContent = "Problem aplikasi berhasil ditambahkan!";
            messageEl.className = "text-green-600";

            // Reset form
            e.target.reset();
        } else {
            messageEl.textContent = "Gagal menambahkan problem aplikasi.";
            messageEl.className = "text-red-500";
        }
    } catch (error) {
        console.error("Error saat menambahkan problem aplikasi:", error);

        if (error.response && error.response.status === 401) {
            messageEl.textContent = "Sesi login berakhir. Silakan login ulang.";
        } else {
            messageEl.textContent = `Gagal menyimpan: ${error.message}`;
        }
        messageEl.className = "text-red-500";
    }
}
