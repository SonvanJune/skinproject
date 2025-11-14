class SingleSelect {
    constructor(element, options = {}) {
        let defaults = {
            placeholder: "Select item",
            search: true,
            data: [],
            onChange: function () {},
            onSelect: function () {},
            onUnselect: function () {},
        };
        this.options = Object.assign(defaults, options);
        this.selectElement =
            typeof element === "string"
                ? document.querySelector(element)
                : element;

        // Lấy các dữ liệu từ thuộc tính data- trong HTML
        for (const prop in this.selectElement.dataset) {
            if (this.options[prop] !== undefined) {
                if (prop === "search") {
                    // Chuyển 'true' thành true và 'false' thành false
                    this.options[prop] =
                        this.selectElement.dataset[prop] === "true";
                } else {
                    this.options[prop] = this.selectElement.dataset[prop];
                }
            }
        }

        // Lấy các option từ select nếu không có data được truyền vào
        if (!this.options.data.length) {
            let options = this.selectElement.querySelectorAll("option");
            for (let i = 0; i < options.length; i++) {
                this.options.data.push({
                    value: options[i].value,
                    text: options[i].innerHTML,
                    html: options[i].getAttribute("data-html"),
                    selected: options[i].hasAttribute("selected"),
                });
            }
        }

        // Tạo input ẩn để lưu giá trị đã chọn
        this.hiddenInput = document.createElement("input");
        this.hiddenInput.type = "hidden";
        this.hiddenInput.name = this.selectElement.name || "single-select";
        this.selectElement.parentNode.insertBefore(
            this.hiddenInput,
            this.selectElement
        );

        // Tạo element tùy chỉnh
        this.element = this._template();
        this.selectElement.replaceWith(this.element);
        this._updateSelected();
        this._eventHandlers();
    }

    _template() {
        let optionsHTML = "";
        for (let i = 0; i < this.options.data.length; i++) {
            optionsHTML += `
        <div class="multi-select-option${
            this.options.data[i].selected ? " multi-select-selected" : ""
        }" data-value="${this.options.data[i].value}">
            <span class="multi-select-option-radio"></span>
            <span class="multi-select-option-text">${
                this.options.data[i].html
                    ? this.options.data[i].html
                    : this.options.data[i].text
            }</span>
        </div>
    `;
        }
        let template = `
<div class="multi-select ${this.name}" style="width: 100%;">
    <div class="multi-select-header">
        <span class="multi-select-header-placeholder">${
            this.options.placeholder
        }</span>
    </div>
    <div class="multi-select-options">
        ${
            this.options.search === true
                ? '<input type="text" class="multi-select-search" placeholder="Search...">'
                : ""
        }
        ${optionsHTML}
    </div>
</div>
`;
        let element = document.createElement("div");
        element.innerHTML = template;
        return element;
    }

    _updateSelected() {
        const selected = this.options.data.find((option) => option.selected);
        if (selected) {
            selected.text = selected.text
                .replace(/<small[^>]*>.*?<\/small>/gi, "")
                .trim();
            this.element.querySelector(
                ".multi-select-header-placeholder"
            ).textContent = selected.text;

            // Cập nhật giá trị input ẩn
            this.hiddenInput.value = selected.value;
        } else {
            // Không có mục nào được chọn
            this.hiddenInput.value = "";
            this.element.querySelector(
                ".multi-select-header-placeholder"
            ).textContent = "Select a category";
        }
    }

    _eventHandlers() {
        let headerElement = this.element.querySelector(".multi-select-header");
        let optionsElement = this.element.querySelector(
            ".multi-select-options"
        );

        this.element
            .querySelectorAll(".multi-select-option")
            .forEach((option) => {
                option.onclick = () => {
                    const isSelected = option.classList.contains(
                        "multi-select-selected"
                    );

                    // Nếu mục đã được chọn, bỏ chọn nó
                    if (isSelected) {
                        option.classList.remove("multi-select-selected");
                        this.options.data.find(
                            (data) => data.value == option.dataset.value
                        ).selected = false;
                    } else {
                        // Nếu mục chưa được chọn, chọn nó
                        this._deselectAll(); // Bỏ chọn tất cả các mục khác
                        option.classList.add("multi-select-selected");
                        this.options.data.find(
                            (data) => data.value == option.dataset.value
                        ).selected = true;
                    }

                    this._updateSelected();
                    this.options.onSelect(
                        option.dataset.value,
                        option.querySelector(".multi-select-option-text")
                            .innerHTML,
                        option
                    );
                    this.options.onChange(
                        option.dataset.value,
                        option.querySelector(".multi-select-option-text")
                            .innerHTML,
                        option
                    );

                    // Đóng dropdown khi chọn một mục
                    optionsElement.style.display = "none";
                    headerElement.classList.remove(
                        "multi-select-header-active"
                    );
                };
            });

        headerElement.onclick = (e) => {
            e.stopPropagation(); // Ngăn sự kiện lan ra ngoài
            headerElement.classList.toggle("multi-select-header-active");
            optionsElement.style.display = headerElement.classList.contains(
                "multi-select-header-active"
            )
                ? "block"
                : "none";
        };

        // Tắt dropdown khi bấm ra ngoài
        // document.addEventListener("click", () => {
        //     optionsElement.style.display = "none";
        //     headerElement.classList.remove("multi-select-header-active");
        // });

        // Xử lý search
        if (this.options.search === true) {
            let search = this.element.querySelector(".multi-select-search");
            search.oninput = () => {
                this.element
                    .querySelectorAll(".multi-select-option")
                    .forEach((option) => {
                        option.style.display =
                            option
                                .querySelector(".multi-select-option-text")
                                .innerHTML.toLowerCase()
                                .indexOf(search.value.toLowerCase()) > -1
                                ? "flex"
                                : "none";
                    });
            };
        }
    }

    _deselectAll() {
        this.element
            .querySelectorAll(".multi-select-option")
            .forEach((option) => {
                option.classList.remove("multi-select-selected");
                this.options.data.find(
                    (data) => data.value == option.dataset.value
                ).selected = false;
            });
    }
}

// Khởi tạo SingleSelect
new SingleSelect("#category", {
    search: true,
});
