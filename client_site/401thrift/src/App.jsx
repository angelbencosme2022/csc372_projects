import { Routes, Route } from "react-router-dom";
import { useState } from "react";
import Header from "./components/Header";
import Footer from "./components/Footer";
import HomePage from "./pages/HomePage";
import AboutPage from "./pages/AboutPage";
import ShopPage from "./pages/ShopPage";
import ContactPage from "./pages/ContactPage";
import CartPage from "./pages/CartPage";
import { products as seedProducts } from "./data/siteData";

function App() {
  const [cartItems, setCartItems] = useState([]);

  const handleAddToCart = (product, purchaseType) => {
    setCartItems((currentItems) => {
      const existingIndex = currentItems.findIndex(
        (item) => item.product.id === product.id && item.purchaseType === purchaseType
      );

      if (existingIndex === -1) {
        return [
          ...currentItems,
          {
            product,
            purchaseType,
            quantity: 1,
          },
        ];
      }

      return currentItems.map((item, index) => {
        if (index !== existingIndex) {
          return item;
        }

        if (purchaseType === "bid") {
          return item;
        }

        return {
          ...item,
          quantity: item.quantity + 1,
        };
      });
    });
  };

  const handleUpdateQuantity = (productId, purchaseType, quantity) => {
    setCartItems((currentItems) =>
      currentItems
        .map((item) => {
          if (item.product.id !== productId || item.purchaseType !== purchaseType) {
            return item;
          }

          return {
            ...item,
            quantity,
          };
        })
        .filter((item) => item.quantity > 0)
    );
  };

  const handleRemoveItem = (productId, purchaseType) => {
    setCartItems((currentItems) =>
      currentItems.filter(
        (item) => item.product.id !== productId || item.purchaseType !== purchaseType
      )
    );
  };

  const handleClearCart = () => {
    setCartItems([]);
  };

  const subtotal = cartItems.reduce((sum, item) => {
    const price =
      item.purchaseType === "bid" ? item.product.bidPrice : item.product.buyPrice;
    return sum + price * item.quantity;
  }, 0);

  const shipping = cartItems.length > 0 ? 5.99 : 0;
  const total = subtotal + shipping;
  const cartCount = cartItems.reduce((sum, item) => sum + item.quantity, 0);

  return (
    <div className="site-shell">
      <Header cartCount={cartCount} />
      <main>
        <Routes>
          <Route path="/" element={<HomePage />} />
          <Route path="/about" element={<AboutPage />} />
          <Route
            path="/shop"
            element={<ShopPage products={seedProducts} onAddToCart={handleAddToCart} />}
          />
          <Route path="/contact" element={<ContactPage />} />
          <Route
            path="/cart"
            element={
              <CartPage
                cartItems={cartItems}
                subtotal={subtotal}
                shipping={shipping}
                total={total}
                onUpdateQuantity={handleUpdateQuantity}
                onRemoveItem={handleRemoveItem}
                onClearCart={handleClearCart}
              />
            }
          />
        </Routes>
      </main>
      <Footer />
    </div>
  );
}

export default App;
