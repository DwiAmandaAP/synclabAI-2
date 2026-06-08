document.addEventListener("DOMContentLoaded", function () {
  const search = document.getElementById("search");
  const filter = document.getElementById("filter");
  const cards = document.querySelectorAll(".card");

  function update() {
    const keyword = search ? search.value.toLowerCase() : "";
    const kategori = filter ? filter.value : "all";

    cards.forEach(card => {
      const text = card.innerText.toLowerCase();
      const kategoriCard = card.dataset.kategori || "";

      const cocokSearch = text.includes(keyword);
      const cocokFilter = kategori === "all" || kategoriCard === kategori;

      if (cocokSearch && cocokFilter) {
        card.style.display = "flex";
      } else {
        card.style.display = "none";
      }
    });
  }

  if (search) search.addEventListener("input", update);
  if (filter) filter.addEventListener("change", update);
});