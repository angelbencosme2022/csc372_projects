import SectionHeading from "../components/SectionHeading";
import { values } from "../data/siteData";

function AboutPage() {
  return (
    <section className="content">
      <SectionHeading
        title="About 401 Thrift"
        intro="401 Thrift was built around the idea that sustainable fashion should feel personal, affordable, and exciting."
      />

      <div className="about-layout">
        <div className="about-column">
          <h3>Our Mission</h3>
          <p>
            We provide access to high-quality vintage and secondhand clothing while reducing
            fashion waste and promoting conscious shopping habits.
          </p>
          <p>
            Each item in the collection is chosen for its character, condition, and styling
            potential. The aim is to make sustainable fashion feel approachable rather than
            difficult to find.
          </p>
        </div>

        <div className="about-column">
          <h3>Our Story</h3>
          <p>
            What started as hours spent hunting through thrift stores and estate sales turned
            into a curated online destination for vintage enthusiasts and casual shoppers alike.
          </p>
          <p>
            Instead of making customers dig through crowded racks, the site brings the best finds
            together in one place while keeping the discovery aspect that makes thrifting fun.
          </p>
        </div>
      </div>

      <section className="values-block">
        <SectionHeading
          title="Our Values"
          intro="These principles guide both the tone of the brand and the decisions behind the shopping experience."
        />
        <div className="values-grid">
          {values.map((value) => (
            <article className="value-card" key={value.title}>
              <h3>{value.title}</h3>
              <p>{value.description}</p>
            </article>
          ))}
        </div>
      </section>

      <section className="about-cta">
        <h2>Vintage style with a second life</h2>
        <p>
          The store stays focused on unique finds, sustainable choices, and a shopping
          experience that still feels curated rather than generic.
        </p>
      </section>
    </section>
  );
}

export default AboutPage;
