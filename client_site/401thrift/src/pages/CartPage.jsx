import { Link } from "react-router-dom";
import SectionHeading from "../components/SectionHeading";

function CartPage({
  cartItems,
  subtotal,
  shipping,
  total,
  onUpdateQuantity,
  onRemoveItem,
  onClearCart,
}) {
  return (
    <section className="content">
      <SectionHeading
        title="Your Cart"
        intro="Review the products you have selected. Buy Now items can be adjusted, while bids stay at one active entry per item."
      />

      {cartItems.length === 0 ? (
        <div className="cart-empty">
          <p>Your cart is empty right now.</p>
          <Link className="cta-button" to="/shop">
            Start Shopping
          </Link>
        </div>
      ) : (
        <div className="cart-container">
          <div className="cart-items-list">
            <table className="cart-table">
              <thead>
                <tr>
                  <th>Item</th>
                  <th>Type</th>
                  <th>Price</th>
                  <th>Qty</th>
                  <th>Total</th>
                  <th>Remove</th>
                </tr>
              </thead>
              <tbody>
                {cartItems.map((item) => {
                  const price =
                    item.purchaseType === "bid" ? item.product.bidPrice : item.product.buyPrice;

                  return (
                    <tr key={`${item.product.id}-${item.purchaseType}`}>
                      <td>{item.product.name}</td>
                      <td>
                        <span className={`cart-type-badge cart-type-${item.purchaseType}`}>
                          {item.purchaseType}
                        </span>
                      </td>
                      <td>${price.toFixed(2)}</td>
                      <td>
                        {item.purchaseType === "buy" ? (
                          <input
                            className="qty-input"
                            type="number"
                            min="0"
                            max="10"
                            value={item.quantity}
                            onChange={(event) =>
                              onUpdateQuantity(
                                item.product.id,
                                item.purchaseType,
                                Number(event.target.value)
                              )
                            }
                          />
                        ) : (
                          <span>1</span>
                        )}
                      </td>
                      <td>${(price * item.quantity).toFixed(2)}</td>
                      <td>
                        <button
                          className="remove-btn"
                          type="button"
                          onClick={() => onRemoveItem(item.product.id, item.purchaseType)}
                        >
                          ×
                        </button>
                      </td>
                    </tr>
                  );
                })}
              </tbody>
            </table>

            <button className="clear-btn" type="button" onClick={onClearCart}>
              Clear Cart
            </button>
          </div>

          <aside className="cart-summary">
            <h3>Order Summary</h3>
            <div className="summary-line">
              <span>Subtotal</span>
              <span>${subtotal.toFixed(2)}</span>
            </div>
            <div className="summary-line">
              <span>Shipping</span>
              <span>${shipping.toFixed(2)}</span>
            </div>
            <div className="summary-line summary-total">
              <strong>Total</strong>
              <strong>${total.toFixed(2)}</strong>
            </div>
            <p className="payment-note">
              Demo checkout is preserved in concept here. The focus of this React version is the
              client-side shopping experience and form interactions.
            </p>
            <Link className="cta-button checkout-link" to="/contact">
              Continue to Support
            </Link>
          </aside>
        </div>
      )}
    </section>
  );
}

export default CartPage;
