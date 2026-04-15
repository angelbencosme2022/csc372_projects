import { NavLink } from "react-router-dom";
import { useState } from "react";
import { navLinks } from "../data/siteData";

function Header({ cartCount }) {
  const [menuOpen, setMenuOpen] = useState(false);

  return (
    <header className="header">
      <div className="header-inner">
        <NavLink className="brand" to="/" onClick={() => setMenuOpen(false)}>
          <span className="brand-mark">401</span>
          <span className="brand-text">Thrift</span>
        </NavLink>

        <button
          className="menu-toggle"
          type="button"
          aria-expanded={menuOpen}
          aria-label="Toggle navigation"
          onClick={() => setMenuOpen((current) => !current)}
        >
          {menuOpen ? "Close" : "Menu"}
        </button>

        <nav className={`site-nav ${menuOpen ? "open" : ""}`}>
          {navLinks.map((link) => (
            <NavLink
              key={link.to}
              to={link.to}
              className={({ isActive }) => (isActive ? "nav-link active" : "nav-link")}
              onClick={() => setMenuOpen(false)}
            >
              {link.label}
              {link.to === "/cart" && cartCount > 0 ? (
                <span className="cart-badge">{cartCount}</span>
              ) : null}
            </NavLink>
          ))}
        </nav>
      </div>
    </header>
  );
}

export default Header;
