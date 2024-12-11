import twemoji from "@twemoji/api";

document.addEventListener("DOMContentLoaded", () => {
    const emojiContainer = document.querySelector("#emoji-container");
    const emojis = ["🚀", "💡", "🔐", "💵", "☠️", "📎", "📆", "📝", "📩", "💬", "⭐"];

    emojis.forEach((emoji, index) => {
        const labelElement = document.createElement("label");
        labelElement.classList.add("emoji-item");
        labelElement.setAttribute("data-index", index);

        const checkboxElement = document.createElement("input");
        checkboxElement.type = "checkbox";
        checkboxElement.classList.add("hidden-checkbox");
        checkboxElement.style.display = "none";
        // Agregar el atributo "name" basado en el emoji
        checkboxElement.setAttribute("name", emoji);

        const emojiElement = document.createElement("div");
        emojiElement.innerHTML = twemoji.parse(emoji);
        emojiElement.classList.add("emoji-icon");

        labelElement.appendChild(checkboxElement);
        labelElement.appendChild(emojiElement);

        labelElement.addEventListener("click", (e) => {
            handleEmojiSelection(labelElement, checkboxElement, emoji);
            e.preventDefault();
        });

        emojiContainer.appendChild(labelElement);
    });
});

function handleEmojiSelection(labelElement, checkboxElement, emoji) {
    const allCheckboxes = document.querySelectorAll(".hidden-checkbox");

    if (checkboxElement.checked) {
        // Si ya estaba seleccionado, lo desmarcamos
        checkboxElement.checked = false;
        labelElement.classList.remove("selected");
        console.log("Emoji desmarcado:", emoji);
    } else {
        // Si no estaba seleccionado, desmarcamos todos y marcamos el actual
        allCheckboxes.forEach((cb) => {
            cb.checked = false;
            cb.parentElement.classList.remove("selected");
        });

        checkboxElement.checked = true;
        labelElement.classList.add("selected");
        console.log("Emoji seleccionado:", emoji);
    }
}
