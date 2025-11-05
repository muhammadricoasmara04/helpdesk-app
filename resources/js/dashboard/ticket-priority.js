import axios from "axios";

document.addEventListener("DOMContentLoaded", () => {
    getTicketPriorities();
    storeTicketPriorities();
});

async function getTicketPriorities() {
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
                                href="ticket-priority/${item.id}" 
                                class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-md transition flex items-center justify-center"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.8" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 12s3.75-7.5 9.75-7.5S21.75 12 21.75 12s-3.75 7.5-9.75 7.5S2.25 12 2.25 12z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                            </a>
                            <button
                                class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-md transition flex items-center justify-center delete-btn"
                                data-id="${item.id}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                            </button>
                        </div>
                    </td>
        `;
           
                tableBody.appendChild(row);
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
        const messageEl = document.getElementById("responseMessage");

        if (!token) {
            messageEl.textContent =
                "Belum login. Silakan login terlebih dahulu.";
            messageEl.className = "text-red-500";
            return;
        }

        const formData = {
            name: document.getElementById("name").value.trim(),
           
            description: document.getElementById("description").value.trim(),
        };

        if (!formData.name || !formData.description) {
            messageEl.textContent = "Semua field wajib diisi.";
            messageEl.className = "text-red-500";
            return;
        }

        try {
            messageEl.textContent = "Menyimpan data...";
            messageEl.className = "text-gray-500";

            const response = await axios.post(
                `${baseUrl}/ticket-priority`,
                formData,
                {
                    headers: { Authorization: `Bearer ${token}` },
                }
            );

            if (response.data.success) {
                messageEl.textContent = "Prioritas berhasil ditambahkan!";
                messageEl.className = "text-green-600";
                form.reset();
            } else {
                messageEl.textContent = "Gagal menambahkan prioritas.";
                messageEl.className = "text-red-500";
            }
        } catch (error) {
            console.error("Error:", error);
            messageEl.textContent = `Gagal menyimpan: ${error.message}`;
            messageEl.className = "text-red-500";
        }
    });
}
