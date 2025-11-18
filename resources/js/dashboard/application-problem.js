import axios from "axios";
import Swal from "sweetalert2";

document.addEventListener("DOMContentLoaded", () => {
    getDataApplicationProblems();
    loadApplications();
    loadTicketPriorities();
    console.log("🟢 File applicationProblems.js berhasil dimuat!");
});

async function getDataApplicationProblems() {
    const user = JSON.parse(localStorage.getItem("user") || "{}");
    const userRole = user.role || null;
    console.log("Role user:", userRole);
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

        problems.forEach((problem, index) => {
            const app = applications.find(
                (a) => a.id === problem.application_id
            );
            const appName = app?.application_name || "-";

            const row = document.createElement("tr");
            row.classList.add(
                "hover:bg-gray-50",
                "transition",
                "odd:bg-white",
                "even:bg-gray-50"
            );

            let appLink = `/dashboard/application-problems/${problem.id}`;
            if (userRole === "staff") {
                appLink = `/dashboard/staff/application-problems/${problem.id}`;
            }

            row.innerHTML = `
    <td class="px-6 py-3 text-gray-700 font-medium text-center">${
        index + 1
    }</td>
    <td class="px-6 py-3">${problem.problem_name || "-"}</td>
    <td class="px-6 py-3">${problem.description || "-"}</td>
    <td class="px-6 py-3">${appName}</td>
    <td class="px-6 py-3">${problem.creator?.name || "-"}</td>
    <td class="px-6 py-3">${problem.updater?.name || "-"}</td>
    <td class="px-6 py-3">${formatDate(problem.created_at)}</td>
    <td class="px-6 py-3 text-center flex items-center justify-center gap-2">
        <a href="${appLink}"
            class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-md transition"
            title="Lihat Detail">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="1.8" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.25 12s3.75-7.5 9.75-7.5S21.75 12 21.75 12s-3.75 7.5-9.75 7.5S2.25 12 2.25 12z" />
                <circle cx="12" cy="12" r="3" />
            </svg>
        </a>
         ${
             userRole === "admin"
                 ? `
        <button class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-md transition delete-btn"
            data-id="${problem.id}" title="Hapus">
           <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
</svg>
        </button>
         `
                 : ""
         }
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

        selectApp.innerHTML = `<option value="">Pilih Layanan...</option>`;
        applications.forEach((app) => {
            const option = document.createElement("option");
            option.value = app.id;
            option.textContent = app.application_name;
            selectApp.appendChild(option);
        });
    } catch (error) {
        console.error("Gagal memuat Layanan:", error);
        selectApp.innerHTML = `<option value="">Gagal memuat data Layanan</option>`;
    }
}

document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("applicationProblemForm");
    if (form) {
        form.addEventListener("submit", storeApplicationProblem);
    }
});

async function loadTicketPriorities() {
    const baseUrl = import.meta.env.VITE_API_BASE_URL;
    const token = localStorage.getItem("token");
    const selectPriority = document.getElementById("ticket_priority_id");
    if (!selectPriority) return;

    try {
        const res = await axios.get(`${baseUrl}/ticket-priority`, {
            headers: { Authorization: `Bearer ${token}` },
        });
        const priorities = res.data.data || [];

        selectPriority.innerHTML = `<option value="">Pilih Prioritas...</option>`;
        priorities.forEach((p) => {
            const opt = document.createElement("option");
            opt.value = p.id;
            opt.textContent = p.name;
            selectPriority.appendChild(opt);
        });
    } catch (err) {
        console.error("Gagal memuat prioritas:", err);
        selectPriority.innerHTML = `<option value="">Gagal memuat prioritas</option>`;
    }
}

async function storeApplicationProblem(e) {
    e.preventDefault();

    const baseUrl = import.meta.env.VITE_API_BASE_URL;
    const token = localStorage.getItem("token");

    if (!token) {
        Swal.fire({
            icon: "error",
            title: "Belum login",
            text: "Silakan login terlebih dahulu.",
        });
        return;
    }

    const formData = {
        problem_name: document.getElementById("problem_name").value.trim(),
        description: document.getElementById("description").value.trim(),
        application_id: document.getElementById("application_id").value.trim(),
        ticket_priority_id: document
            .getElementById("ticket_priority_id")
            .value.trim(),
    };

    if (
        !formData.problem_name ||
        !formData.description ||
        !formData.application_id ||
        !formData.ticket_priority_id
    ) {
        Swal.fire({
            icon: "error",
            title: "Form incomplete",
            text: "Semua field wajib diisi.",
        });
        return;
    }

    try {
        Swal.fire({
            icon: "info",
            title: "Menyimpan data...",
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            },
        });

        const response = await axios.post(
            `${baseUrl}/application-problems`,
            formData,
            {
                headers: { Authorization: `Bearer ${token}` },
            }
        );

        Swal.close(); // tutup loading

        if (response.data.success) {
            Swal.fire({
                icon: "success",
                title: "Berhasil!",
                text: "Masalah layanan berhasil ditambahkan!",
                timer: 3000,
                showConfirmButton: false,
            });

            e.target.reset();

            setTimeout(() => {
                window.location.href =
                    "http://127.0.0.1:8000/dashboard/application-problems";
            }, 3000);
        } else {
            Swal.fire({
                icon: "error",
                title: "Gagal!",
                text: "Gagal menambahkan problem layanan.",
            });
        }
    } catch (error) {
        console.error("Error saat menambahkan problem layanan:", error);

        let errMsg =
            error.response?.status === 401
                ? "Sesi login berakhir. Silakan login ulang."
                : `Gagal menyimpan: ${error.message}`;

        Swal.fire({
            icon: "error",
            title: "Error!",
            text: errMsg,
        });
    }
}

document.addEventListener("click", async (event) => {
    const deleteBtn = event.target.closest(".delete-btn");
    if (!deleteBtn) return;

    const problemId = deleteBtn.dataset.id;
    if (!problemId) return;

    await deleteApplicationProblem(problemId);
});

// ✅ Fungsi async untuk hapus data
async function deleteApplicationProblem(id) {
    const baseUrl = import.meta.env.VITE_API_BASE_URL;
    const token = localStorage.getItem("token");

    if (!token) {
        alert("Belum login. Silakan login terlebih dahulu.");
        return;
    }

    const result = await Swal.fire({
        title: "Yakin ingin menghapus problem ini?",
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Ya, hapus",
        cancelButtonText: "Batal",
    });

    if (!result.isConfirmed) return;

    try {
        const response = await axios.delete(
            `${baseUrl}/application-problems/${id}`,
            {
                headers: { Authorization: `Bearer ${token}` },
            }
        );

        if (response.data.success) {
            Swal.fire({
                icon: "success",
                title: "Berhasil!",
                text: "Masalah layanan berhasil dihapus!",
                timer: 2000,
                showConfirmButton: false,
            });
            await getDataApplicationProblems(); // reload tabel setelah hapus
        } else {
            Swal.fire({
                icon: "error",
                title: "Gagal!",
                text: "Gagal menghapus masalah layanan.",
            });
        }
    } catch (error) {
        console.error("❌ Error saat menghapus:", error);
        alert(
            error.response?.status === 401
                ? "Sesi login berakhir. Silakan login ulang."
                : `Terjadi kesalahan: ${error.message}`
        );
    }
}
