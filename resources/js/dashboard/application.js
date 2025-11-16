import axios from "axios";
import Swal from "sweetalert2";

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

            applications.forEach((app, index) => {
                const row = document.createElement("tr");
                row.classList.add("hover:bg-gray-50", "transition");

                row.innerHTML = `
        <td class="px-6 py-3 text-center font-medium">${index + 1}</td>
        <td class="px-6 py-3">${app.application_name || "-"}</td>
        <td class="px-6 py-3">${app.description || "-"}</td>
        <td class="px-6 py-3">${app.creator?.name || "-"}</td>
        <td class="px-6 py-3">${app.updater?.name || "-"}</td>
        <td class="px-6 py-3">${formatDate(app.created_at)}</td>
        <td class="px-6 py-3 text-center flex justify-center gap-2">
            <a href="/dashboard/application/${app.id}"
               class="bg-blue-600 text-white p-2 rounded-md hover:bg-blue-700 transition"
               title="Detail Aplikasi">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="1.8" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.25 12s3.75-7.5 9.75-7.5S21.75 12 21.75 12s-3.75 7.5-9.75 7.5S2.25 12 2.25 12z" />
                <circle cx="12" cy="12" r="3" />
            </svg>
            </a>
            <button class="bg-red-500 text-white p-2 rounded-md hover:bg-red-600 transition delete-btn" 
                    data-id="${app.id}" title="Hapus Aplikasi">
               <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
</svg>
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
    };

    if (
        !formData.application_name ||
        !formData.application_code ||
        !formData.description
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
            // 🔥 Swal ketika sukses
            Swal.fire({
                icon: "success",
                title: "Berhasil!",
                text: "Aplikasi berhasil ditambahkan!",
                timer: 3000,
                showConfirmButton: false,
            });

            // Reset form
            e.target.reset();

            // Redirect setelah 3 detik
            setTimeout(() => {
                window.location.href =
                    "http://127.0.0.1:8000/dashboard/application";
            }, 3000);
        } else {
            Swal.fire({
                icon: "error",
                title: "Gagal!",
                text: "Gagal menambahkan aplikasi.",
            });
        }
    } catch (error) {
        console.error("Error saat menambahkan aplikasi:", error);

        let errMsg = "Gagal menyimpan data.";

        if (error.response && error.response.status === 401) {
            errMsg = "Sesi login berakhir. Silakan login ulang.";
        } else {
            errMsg = `Gagal: ${error.message}`;
        }

        Swal.fire({
            icon: "error",
            title: "Error!",
            text: errMsg,
        });
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
            document.getElementById("application_code").textContent =
                data.application_code;
            document.getElementById("description").textContent =
                data.description;
            document.getElementById("organization_id").textContent =
                data.organization?.organization;
            document.getElementById("create_id").textContent =
                data.creator?.name || "-";
            document.getElementById("updated_id").textContent =
                data.updater?.name || "-";
        } else {
            detailEl.innerHTML = `<p class="text-red-500">Data aplikasi tidak ditemukan.</p>`;
        }
    } catch (error) {
        console.error("Error getDataById:", error);
        detailEl.innerHTML = `<p class="text-red-500">Terjadi kesalahan saat memuat data.</p>`;
    }
}

async function deleteApplication(id) {
    const baseUrl = import.meta.env.VITE_API_BASE_URL;
    const token = localStorage.getItem("token");

    if (!token) {
        alert("Belum login. Silakan login terlebih dahulu.");
        return;
    }

    const result = await Swal.fire({
        title: "Apakah Anda yakin ingin menghapus aplikasi ini?",
        text: "Tindakan ini tidak dapat dibatalkan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Ya, hapus",
        cancelButtonText: "Batal",
    });

    if (!result.isConfirmed) return;

    try {
        const response = await axios.delete(`${baseUrl}/applications/${id}`, {
            headers: { Authorization: `Bearer ${token}` },
        });

        if (response.data.success) {
            Swal.fire({
                icon: "success",
                title: "Berhasil!",
                text: "Aplikasi berhasil dihapus!",
                timer: 2000,
                showConfirmButton: false,
            });

            // Reload data aplikasi setelah delete
            getDataApplication();
        } else {
            Swal.fire({
                icon: "error",
                title: "Gagal!",
                text: "Gagal menghapus aplikasi.",
            });
        }
    } catch (error) {
        console.error("Error saat menghapus aplikasi:", error);
        if (error.response && error.response.status === 401) {
            alert("Sesi login berakhir. Silakan login ulang.");
        } else {
            alert(`Gagal menghapus aplikasi: ${error.message}`);
        }
    }
}
document.addEventListener("click", (e) => {
    if (e.target && e.target.classList.contains("delete-btn")) {
        const id = e.target.getAttribute("data-id");
        deleteApplication(id);
    }
});
