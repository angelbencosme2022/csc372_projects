import { Link } from "react-router-dom";
import HeroSection from "../components/HeroSection";
import SectionHeading from "../components/SectionHeading";

function HomePage() {
  return (
    <>
      <HeroSection
        title="Welcome to 401 Thrift"
        description="One-of-a-kind vintage clothing, accessories, and secondhand finds, hand-picked, quality-checked, and brought straight to you."
        primaryLink="/shop"
        primaryLabel="Browse Latest Finds"
        secondaryLink="/about"
        secondaryLabel="Learn Our Story"
      />

      <section className="content">
        <div className="story-grid">
          <div className="story-copy">
            <SectionHeading
              title="How It Works"
              intro="Shopping at 401 Thrift should feel simple and exciting. Browse current pieces, review pricing and condition, then decide whether to purchase right away or place a bid."
            />
            <p>
              We curate vintage clothing, accessories, and unique secondhand finds for people
              who want style with personality. Every item is selected for quality, photographed
              with care, and presented with practical details so customers can shop confidently.
            </p>
            <p>
              New pieces are added regularly, which keeps the shop fresh and gives customers a
              reason to check back often. The goal is to keep the thrill of discovery while
              making online thrift shopping easier to navigate.
            </p>
          </div>

          <aside className="feature-panel">
            <div className="feature-card">
              <h3>Buy or Bid</h3>
              <p>Choose immediate checkout or a lower starting point through bidding.</p>
            </div>
            <div className="feature-card">
              <h3>Curated Inventory</h3>
              <p>Each listing is selected to match the store’s vintage and sustainable focus.</p>
            </div>
            <div className="feature-card">
              <h3>Transparent Details</h3>
              <p>Pricing, category, and condition notes are easy to scan before purchase.</p>
            </div>
          </aside>
        </div>

        <section className="mission-banner">
          <h2>Why Choose 401 Thrift?</h2>
          <p>
            At 401 Thrift, sustainability meets style. Shoppers get access to unique pieces that
            stand out from fast fashion while supporting reuse and more conscious consumption.
          </p>
          <Link className="cta-button" to="/contact">
            Ask a Question
          </Link>
        </section>
      </section>
    </>
  );
}

export default HomePage;
