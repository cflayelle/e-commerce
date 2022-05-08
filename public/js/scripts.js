console.log("👋")

const btnProductFilter = document.getElementById("btn-product-filter")
const navProductFilter = document.getElementById("nav-product-filter")
const navProductFilterClose = document.querySelector("#nav-product-filter .nav-close")

btnProductFilter.addEventListener("click", () => {
    navProductFilter.classList.add("active")
})

navProductFilterClose.addEventListener("click", () => {
    navProductFilter.classList.remove("active")
})