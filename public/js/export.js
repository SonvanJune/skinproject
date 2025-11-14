function exportToExcel() {
    const table = document.querySelector("#revenueTable");
    const workbook = new ExcelJS.Workbook();
    const worksheet = workbook.addWorksheet("Revenue");

    const headers = [];
    table.querySelectorAll("thead th").forEach((th, index, array) => {
        if (index < array.length - 1) {
            headers.push(th.innerText);
        }
    });
    const headerRow = worksheet.addRow(headers);

    const rows = table.querySelectorAll("tbody tr");
    rows.forEach((row, rowIndex) => {
        const data = [];
        row.querySelectorAll("td").forEach((td, index, array) => {
            if (index < array.length - 1) {
                data.push(td.innerText.trim());
            }
        });
        const excelRow = worksheet.addRow(data);

        if (rowIndex === rows.length - 1) {
            const totalColumns = headers.length;
            excelRow.getCell(1).value = "Total Revenue:";

            if (totalColumns > 1) {
                worksheet.mergeCells(excelRow.number, 2, excelRow.number, totalColumns);
            }

            excelRow.eachCell((cell, colNumber) => {
                cell.font = { bold: true };
                cell.fill = {
                    type: 'pattern',
                    pattern: 'solid',
                    fgColor: { argb: 'FFE8E8E8' } 
                };
                cell.alignment = {
                    horizontal: colNumber === 1 ? "left" : "right",
                    vertical: "middle",
                    wrapText: true
                };
                cell.border = {
                    top: { style: 'thin' },
                    left: { style: 'thin' },
                    bottom: { style: 'thin' },
                    right: { style: 'thin' }
                };
            });
        }
    });

    worksheet.eachRow({ includeEmpty: false }, function (row, rowNumber) {
        row.eachCell({ includeEmpty: false }, function (cell) {
            cell.border = {
                top: { style: 'thin' },
                left: { style: 'thin' },
                bottom: { style: 'thin' },
                right: { style: 'thin' }
            };
            cell.alignment = {
                vertical: 'middle',
                horizontal: 'center',
                wrapText: true
            };
        });

        if (rowNumber === 1) {
            row.eachCell(cell => {
                cell.fill = {
                    type: 'pattern',
                    pattern: 'solid',
                    fgColor: { argb: 'FFCCE5FF' }
                };
                cell.font = { bold: true, color: { argb: 'FF000000' } };
            });
        }
    });

    workbook.xlsx.writeBuffer().then((data) => {
        const startDate = document.getElementById('startDate');
        const endDate = document.getElementById('endDate');

        const partsStart = startDate.value.split('T')[0];
        const partsEnd = endDate.value.split('T')[0];

        const blob = new Blob([data], {
            type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
        });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement("a");
        a.href = url;
        a.download = webName + "_" + "Revenue_" + partsStart + "_" + partsEnd +".xlsx";
        a.click();
        window.URL.revokeObjectURL(url);
    });
}
