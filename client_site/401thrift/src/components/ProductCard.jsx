function ProductCard({ product, onAddToCart }) {
  return (
    <article className="product-card">
      <div className="product-visual">
        <span>{product.imageLabel}</span>
      </div>
      <div className="product-info">
        <div className="product-meta">
          <p className="product-category">{product.category}</p>
          <h3>{product.name}</h3>
        </div>
        <p className="product-description">{product.description}</p>
        <p className="product-condition">{product.condition}</p>
        <p className="product-price">Buy Now: ${product.buyPrice.toFixed(2)}</p>
        <p className="product-bid">Current Bid: ${product.bidPrice.toFixed(2)}</p>
        <div className="product-actions">
          <button className="buy-btn" type="button" onClick={() => onAddToCart(product, "buy")}>
            Buy Now
          </button>
          <button className="bid-btn" type="button" onClick={() => onAddToCart(product, "bid")}>
            Place Bid
          </button>
        </div>
      </div>
    </article>
  );
}

export default ProductCard;
