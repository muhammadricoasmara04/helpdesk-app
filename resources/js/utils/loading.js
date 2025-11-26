// GLOBAL OVERLAY LOADING
export function showLoading() {
    const el = document.getElementById("loading-spinner");
    if (el) el.classList.remove("hidden");
}

export function hideLoading() {
    const el = document.getElementById("loading-spinner");
    if (el) el.classList.add("hidden");
}

// TABLE LOADING
export function showTableLoading(
    tbodyId = "tickets-table-body",
    colspan = 9,
    message = "Memuat data..."
) {
    const tableBody = document.getElementById(tbodyId);
    if (!tableBody) return;

    tableBody.innerHTML = `
        <tr>
            <td colspan="${colspan}" class="text-center py-6 text-gray-400 italic">
                <div class="inline-flex items-center gap-2 justify-center">
                    <svg class="w-5 h-5 animate-spin text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                    ${message}
                </div>
            </td>
        </tr>
    `;
}

export function hideTableLoading() {
    const row = document.getElementById("loading-row");
    if (row) row.classList.add("hidden");
}
