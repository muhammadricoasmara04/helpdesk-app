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
            data.forEach((item) => {
                const row = document.createElement("tr");
                row.innerHTML = `
                    <td class="border px-4 py-2">${item.name || "-"}</td>
                    <td class="border px-4 py-2">${item.slug || "-"}</td>
                    <td class="border px-4 py-2">${item.description || "-"}</td>
                    <td class="border px-4 py-2">${formatDate(
                        item.created_at
                    )}</td>
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
            slug: document.getElementById("slug").value.trim(),
            description: document.getElementById("description").value.trim(),
        };

        if (!formData.name || !formData.slug || !formData.description) {
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
