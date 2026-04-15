import { Link } from "react-router-dom";

function HeroSection({ title, description, primaryLink, primaryLabel, secondaryLink, secondaryLabel }) {
  return (
    <section className="hero">
      <div className="hero-copy">
        <p className="eyebrow">Curated vintage and secondhand style</p>
        <h1>{title}</h1>
        <p>{description}</p>
        <div className="hero-actions">
          <Link className="cta-button" to={primaryLink}>
            {primaryLabel}
          </Link>
          {secondaryLink && secondaryLabel ? (
            <Link className="ghost-button" to={secondaryLink}>
              {secondaryLabel}
            </Link>
          ) : null}
        </div>
      </div>
      <div className="hero-panel">
        <div className="hero-stat">
          <span>Weekly Drops</span>
          <strong>New finds added regularly</strong>
        </div>
        <div className="hero-stat">
          <span>Flexible Shopping</span>
          <strong>Choose Buy Now or place a bid</strong>
        </div>
        <div className="hero-stat">
          <span>Mission</span>
          <strong>Sustainable fashion with personality</strong>
        </div>
      </div>
    </section>
  );
}

export default HeroSection;
