 const header = document.querySelector(".main-nav");
            const toggleClass = "is-sticky";

            window.addEventListener("scroll", () => {
            const currentScroll = window.pageYOffset;
            if (currentScroll > 150) {
                header.classList.add(toggleClass);
            } else {
                header.classList.remove(toggleClass);
            }
            });