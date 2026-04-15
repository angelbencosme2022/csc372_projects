function FilterChips({ categories, activeCategory, onChange }) {
  return (
    <div className="filter-section" aria-label="Filter products by category">
      {categories.map((category) => (
        <button
          key={category}
          className={category === activeCategory ? "filter-btn active" : "filter-btn"}
          type="button"
          onClick={() => onChange(category)}
        >
          {category === "all" ? "All Items" : category}
        </button>
      ))}
    </div>
  );
}

export default FilterChips;
