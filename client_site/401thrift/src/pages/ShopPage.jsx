import { useState } from "react";
import SectionHeading from "../components/SectionHeading";
import FilterChips from "../components/FilterChips";
import ProductCard from "../components/ProductCard";

function ShopPage({ products, onAddToCart }) {
  const [activeCategory, setActiveCategory] = useState("all");
  const [showGuide, setShowGuide] = useState(true);

  const categories = ["all", ...new Set(products.map((product) => product.category))];

  const visibleProducts =
    activeCategory === "all"
      ? products
      : products.filter((product) => product.category === activeCategory);

  return (
    <section className="content">
      <SectionHeading
        title="Shop Our Collection"
        intro="Browse curated vintage and secondhand pieces. Each listing includes buy-now pricing, current bid pricing, and a quick condition note."
      />

      <div className="shop-topbar">
        <FilterChips
          categories={categories}
          activeCategory={activeCategory}
          onChange={setActiveCategory}
        />

        <button
          className="guide-toggle"
          type="button"
          onClick={() => setShowGuide((current) => !current)}
        >
          {showGuide ? "Hide Bidding Guide" : "Show Bidding Guide"}
        </button>
      </div>

      {showGuide ? (
        <div className="shop-info">
          <h3>How Bidding Works</h3>
          <ol className="shop-guide-list">
            <li>Compare the Buy Now price with the current bid price.</li>
            <li>Add a bid if you want the lower-price auction option.</li>
            <li>Use Buy Now if you want to secure the item immediately.</li>
          </ol>
          <p>Shipping starts at $5.99, and support questions can be sent through the contact page.</p>
        </div>
      ) : null}

      <div className="products-grid">
        {visibleProducts.map((product) => (
          <ProductCard key={product.id} product={product} onAddToCart={onAddToCart} />
        ))}
      </div>
    </section>
  );
}

export default ShopPage;
