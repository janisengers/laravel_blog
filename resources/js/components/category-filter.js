export function categoryFilter() {
    return {
        showLeftArrow: false,
        showRightArrow: false,
        
        init() {
            this.$nextTick(() => {
                this.updateArrowVisibility();
                
                if (this.$refs.categoriesContainer) {
                    this.$refs.categoriesContainer.addEventListener('scroll', () => {
                        this.updateArrowVisibility();
                    });
                }
                
                window.addEventListener('resize', () => {
                    this.updateArrowVisibility();
                });
            });
        },
        
        scrollCategories(direction) {
            const container = this.$refs.categoriesContainer;
            const scrollAmount = 150;
            
            if (direction === 'left') {
                container.scrollLeft -= scrollAmount;
            } else {
                container.scrollLeft += scrollAmount;
            }
            
            setTimeout(() => {
                this.updateArrowVisibility();
            }, 100);
        },
        
        updateArrowVisibility() {
            const container = this.$refs.categoriesContainer;
            if (!container) return;
            
            this.showLeftArrow = container.scrollLeft > 5;
            const maxScroll = container.scrollWidth - container.clientWidth;
            this.showRightArrow = container.scrollLeft < maxScroll - 5;
        },
        
        isCategoryActive(categoryId) {
            return this.$store.posts.selectedCategories.includes(categoryId.toString());
        },
        
        toggleCategory(element, event) {      
            const checkbox = element.querySelector('input[type="checkbox"]');
            const categoryId = checkbox.value;
            const selectedCategories = this.$store.posts.selectedCategories;
            
            if (selectedCategories.includes(categoryId)) {
                this.$store.posts.selectedCategories = selectedCategories.filter(id => id !== categoryId);
            } else {
                this.$store.posts.selectedCategories.push(categoryId);
            }
            
            this.$store.posts.loadPosts({ replace: true });
        }
    }
} 