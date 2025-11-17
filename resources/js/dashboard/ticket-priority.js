import axios from "axios";
import Swal from "sweetalert2";

document.addEventListener("DOMContentLoaded", () => {
    getTicketPriorities();
    storeTicketPriorities();
});

async function getTicketPriorities() {
    const user = JSON.parse(localStorage.getItem("user") || "{}");
    const userRole = user.role || null;
    console.log("Role user:", userRole);
    const baseUrl = import.meta.env.VITE_API_BASE_URL;
    const tableBody = document.getElementById("ticket-priority-table-body");

    const setMessageRow = (message) => {
        tableBody.innerHTML = `
            <tr>
                <td colspan="4" class="text-center py-4 text-gray-500">${message}</td>
            </tr>
        `;
    };

    try {
        const token = localStorage.getItem("token");
        if (!token) {
            setMessageRow("Belum login. Silakan login terlebih dahulu.");
            return;
        }
        const response = await axios.get(`${baseUrl}/ticket-priority`, {
            headers: { Authorization: `Bearer ${token}` },
        });

        const data = response.data?.data || [];

        if (response.data.success && data.length > 0) {
            tableBody.innerHTML = "";

            data.forEach((item, index) => {
                const row = document.createElement("tr");
                row.classList.add("hover:bg-gray-50", "transition");
                row.classList.add(
                    "hover:bg-gray-50",
                    "transition",
                    "odd:bg-white",
                    "even:bg-gray-50"
                );

                let appLink = `ticket-priority/${item.id}`;
                if (userRole === "staff") {
                    appLink = `/dashboard/staff/ticket-priority/${item.id}`;
                }
                row.innerHTML = `
            <td class="px-6 py-3 text-gray-700">${index + 1}</td>
            <td class="px-6 py-3 text-gray-700">${item.name || "-"}</td>
            <td class="px-6 py-3 text-gray-700">${item.slug || "-"}</td>
            <td class="px-6 py-3 text-gray-700">${item.description || "-"}</td>
            <td class="px-6 py-3 text-gray-700">${formatDate(
                item.created_at
            )}</td>
            <td class="py-2 text-center align-middle">
                        <div class="inline-flex items-center justify-center space-x-2">
                            <a 
                                href="${appLink}" 
                                class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-md transition flex items-center justify-center"
                            >
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
                            <button
                                class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-md transition flex items-center justify-center delete-btn"
                                data-id="${item.id}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                            </button>
                            `
                                      : ""
                              }
                        </div>
                    </td>
        `;

                tableBody.appendChild(row);
                initDeleteButtons();
            });
        } else {
            setMessageRow("Belum ada data prioritas.");
        }
    } catch (error) {
        console.error("Error:", error);
        setMessageRow(`Gagal memuat data: ${error.message}`);
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

function storeTicketPriorities() {
    const form = document.getElementById("ticketPriorityForm");
    if (!form) return;

    form.addEventListener("submit", async (e) => {
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
            name: document.getElementById("name").value.trim(),
            description: document.getElementById("description").value.trim(),
        };

        if (!formData.name || !formData.description) {
            Swal.fire({
                icon: "error",
                title: "Form tidak lengkap",
                text: "Semua field wajib diisi.",
            });
            return;
        }

        try {
            // Loading Swal
            Swal.fire({
                icon: "info",
                title: "Menyimpan data...",
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading(),
            });

            const response = await axios.post(
                `${baseUrl}/ticket-priority`,
                formData,
                {
                    headers: { Authorization: `Bearer ${token}` },
                }
            );

            Swal.close();

            if (response.data.success) {
                Swal.fire({
                    icon: "success",
                    title: "Berhasil!",
                    text: "Prioritas berhasil ditambahkan!",
                    timer: 3000,
                    showConfirmButton: false,
                });

                form.reset();

                setTimeout(() => {
                    window.location.href =
                        "http://127.0.0.1:8000/dashboard/ticket-priority";
                }, 3000);
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Gagal!",
                    text: "Gagal menambahkan prioritas.",
                });
            }
        } catch (error) {
            console.error("Error:", error);

            Swal.fire({
                icon: "error",
                title: "Error!",
                text: `Gagal menyimpan: ${error.message}`,
            });
        }
    });
}

async function initDeleteButtons() {
    const deleteButtons = document.querySelectorAll(".delete-btn");

    deleteButtons.forEach((btn) => {
        btn.addEventListener("click", async () => {
            const id = btn.getAttribute("data-id");
            const baseUrl = import.meta.env.VITE_API_BASE_URL;
            const token = localStorage.getItem("token");

            if (!token) {
                Swal.fire(
                    "Belum login",
                    "Silakan login terlebih dahulu.",
                    "warning"
                );
                return;
            }

            // 🔹 Tampilkan konfirmasi SweetAlert
            const result = await Swal.fire({
                title: "Apakah Anda yakin ingin menghapus prioritas ini?",
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
                    `${baseUrl}/ticket-priority/${id}`,
                    {
                        headers: { Authorization: `Bearer ${token}` },
                    }
                );

                if (response.data.success) {
                    await Swal.fire({
                        icon: "success",
                        title: "Berhasil!",
                        text: "Prioritas tiket berhasil dihapus.",
                        timer: 1500,
                        showConfirmButton: false,
                    });

                    // 🔄 Reload tabel setelah hapus
                    await getTicketPriorities();
                    initDeleteButtons(); // penting: re-attach listener karena DOM di-reload
                } else {
                    Swal.fire(
                        "Gagal!",
                        "Tidak dapat menghapus prioritas.",
                        "error"
                    );
                }
            } catch (error) {
                console.error("Error delete:", error);
                Swal.fire(
                    "Terjadi Kesalahan!",
                    error.response?.data?.message || error.message,
                    "error"
                );
            }
        });
    });
}
