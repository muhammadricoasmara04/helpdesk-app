import axios from "axios";

document.addEventListener("DOMContentLoaded", () => {
    const path = window.location.pathname;
    console.log("🔍 Current PATH:", path);
    const match = path.match(/\/dashboard\/application\/([^/]+)/);

    if (match) {
        const id = match[1];
        console.log("🟡 Halaman detail aplikasi. ID =", id);
        getDataById(id);
    } else if (path.includes("/dashboard/application")) {
        console.log("🟢 Halaman daftar aplikasi.");
        getDataApplication();
        const form = document.getElementById("applicationForm");
        if (form) {
            form.addEventListener("submit", storeApplication);
        }
    }
});

async function getDataApplication() {
    const baseUrl = import.meta.env.VITE_API_BASE_URL;
    const tableBody = document.getElementById("application-table-body");
    const setMessageRow = (message) => {
        tableBody.innerHTML = `
            <tr>
                <td colspan="5" class="text-center py-4 text-gray-500">${message}</td>
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

        const response = await axios.get(`${baseUrl}/applications`, {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        });

        const applications = response.data?.data || [];

        if (response.data.success && applications.length > 0) {
            tableBody.innerHTML = "";

            applications.forEach((app) => {
                const row = document.createElement("tr");
                row.innerHTML = `
    <td class="border px-4 py-2">${app.application_name || "-"}</td>
    <td class="border px-4 py-2">${app.description || "-"}</td>
    <td class="border px-4 py-2">${app.create_id || "-"}</td>
    <td class="border px-4 py-2">${app.updated_id || "-"}</td>
    <td class="border px-4 py-2">${formatDate(app.created_at)}</td>
    <td class="border px-4 py-2 text-center">
        <a href="/dashboard/application/${app.id}" 
           class="bg-yellow-500 text-white px-3 py-1 rounded-md hover:bg-yellow-600 transition">
           Detail
        </a>
        <button class="bg-red-500 text-white px-3 py-1 rounded-md hover:bg-red-600 delete-btn" 
                data-id="${app.id}">
            Hapus
        </button>
    </td>
`;
                tableBody.appendChild(row);
            });
        } else {
            setMessageRow("Tidak ada data aplikasi tersedia.");
        }
    } catch (error) {
        console.error("Error saat memuat aplikasi:", error);

        if (error.response && error.response.status === 401) {
            setMessageRow(
                "Sesi login berakhir. Silakan login ulang.",
                "text-red-500"
            );
        } else {
            setMessageRow(
                `Gagal memuat data aplikasi: ${error.message}`,
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
    const form = document.getElementById("applicationForm");
    if (form) {
        form.addEventListener("submit", storeApplication);
    }
});

async function storeApplication(e) {
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
        application_name: document
            .getElementById("application_name")
            .value.trim(),
        application_code: document
            .getElementById("application_code")
            .value.trim(),
        description: document.getElementById("description").value.trim(),
        organization_id: document
            .getElementById("organization_id")
            .value.trim(),
        create_id: crypto.randomUUID(),
        updated_id: crypto.randomUUID(),
    };

    if (
        !formData.application_name ||
        !formData.application_code ||
        !formData.description ||
        !formData.organization_id
    ) {
        messageEl.textContent = "Semua field wajib diisi.";
        messageEl.className = "text-red-500";
        return;
    }

    try {
        messageEl.textContent = "Menyimpan data...";
        messageEl.className = "text-gray-500";

        const response = await axios.post(`${baseUrl}/applications`, formData, {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        });

        if (response.data.success) {
            messageEl.textContent = "Aplikasi berhasil ditambahkan!";
            messageEl.className = "text-green-600";

            // Reset form
            e.target.reset();
        } else {
            messageEl.textContent = "Gagal menambahkan aplikasi.";
            messageEl.className = "text-red-500";
        }
    } catch (error) {
        console.error("Error saat menambahkan aplikasi:", error);

        if (error.response && error.response.status === 401) {
            messageEl.textContent = "Sesi login berakhir. Silakan login ulang.";
        } else {
            messageEl.textContent = `Gagal menyimpan: ${error.message}`;
        }
        messageEl.className = "text-red-500";
    }
}

async function getDataById(id) {
    const baseUrl = import.meta.env.VITE_API_BASE_URL;
    const token = localStorage.getItem("token");
    const detailEl = document.getElementById("applicationDetail");

    try {
        const response = await axios.get(`${baseUrl}/applications/${id}`, {
            headers: { Authorization: `Bearer ${token}` },
        });

        const data = response.data?.data;

        if (response.data.success && data) {
            document.getElementById("application_name").textContent =
                data.application_name;
            document.getElementById("description").textContent =
                data.description;
            document.getElementById("organization_id").textContent =
                data.organization_id;
            document.getElementById("create_id").textContent = data.create_id;
            document.getElementById("updated_id").textContent = data.updated_id;
            console.log("Response data:", response.data);
        } else {
            detailEl.innerHTML = `<p class="text-red-500">Data aplikasi tidak ditemukan.</p>`;
        }
    } catch (error) {
        console.error("Error getDataById:", error);
        detailEl.innerHTML = `<p class="text-red-500">Terjadi kesalahan saat memuat data.</p>`;
    }
}
