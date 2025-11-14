//elements
const searchedCells = document.querySelectorAll(".search-cell");

//default searching
search(document.querySelector("#search-input")?.value);

//handle searching, highlighting
function search(key) {
    const regex = new RegExp(key, "gi");

    searchedCells.forEach((cell) => {
        if (cell.textContent.toLowerCase().includes(key.toLowerCase())) {
            cell.innerHTML = cell.textContent.replace(
                regex,
                '<span class="bg-primary text-white">$&</span>',
            );
        }
    });
}
