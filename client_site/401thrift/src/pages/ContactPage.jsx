import { useState } from "react";
import SectionHeading from "../components/SectionHeading";
import InfoCard from "../components/InfoCard";
import FAQItem from "../components/FAQItem";
import { contactMethods, faqs } from "../data/siteData";

const initialFormState = {
  name: "",
  email: "",
  subject: "",
  message: "",
};

function ContactPage() {
  const [formData, setFormData] = useState(initialFormState);
  const [errors, setErrors] = useState({});
  const [statusMessage, setStatusMessage] = useState("");
  const [openQuestion, setOpenQuestion] = useState(0);

  const handleChange = (event) => {
    const { name, value } = event.target;
    setFormData((current) => ({
      ...current,
      [name]: value,
    }));
  };

  const validateForm = () => {
    const nextErrors = {};

    if (formData.name.trim().length < 2) {
      nextErrors.name = "Name must be at least 2 characters long.";
    }

    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(formData.email)) {
      nextErrors.email = "Enter a valid email address.";
    }

    if (formData.subject.trim().length < 3) {
      nextErrors.subject = "Subject must be at least 3 characters long.";
    }

    if (formData.message.trim().length < 10) {
      nextErrors.message = "Message must be at least 10 characters long.";
    }

    return nextErrors;
  };

  const handleSubmit = (event) => {
    event.preventDefault();
    const nextErrors = validateForm();
    setErrors(nextErrors);

    if (Object.keys(nextErrors).length > 0) {
      setStatusMessage("Please correct the highlighted fields and try again.");
      return;
    }

    setStatusMessage("Thanks for reaching out. Your message is ready to send.");
    setFormData(initialFormState);
  };

  return (
    <section className="content">
      <SectionHeading
        title="Get In Touch"
        intro="Have questions about an item or want to sell vintage pieces to us? Send a message and we will follow up as soon as possible."
      />

      <div className="visitor-info">
        <p>Best for questions about item details, sizing, selling pieces, or order support.</p>
        <p>Typical response time: within 24 hours on weekdays.</p>
      </div>

      <div className="contact-layout">
        <div className="contact-form-container">
          <h3>Send Us a Message</h3>
          <form onSubmit={handleSubmit} noValidate>
            <div className="form-group">
              <label htmlFor="name">Your Name</label>
              <input id="name" name="name" type="text" value={formData.name} onChange={handleChange} />
              {errors.name ? <span className="field-error">{errors.name}</span> : null}
            </div>

            <div className="form-group">
              <label htmlFor="email">Email Address</label>
              <input id="email" name="email" type="email" value={formData.email} onChange={handleChange} />
              {errors.email ? <span className="field-error">{errors.email}</span> : null}
            </div>

            <div className="form-group">
              <label htmlFor="subject">Subject</label>
              <input
                id="subject"
                name="subject"
                type="text"
                value={formData.subject}
                onChange={handleChange}
              />
              {errors.subject ? <span className="field-error">{errors.subject}</span> : null}
            </div>

            <div className="form-group">
              <label htmlFor="message">Message</label>
              <textarea
                id="message"
                name="message"
                rows="6"
                value={formData.message}
                onChange={handleChange}
              />
              {errors.message ? <span className="field-error">{errors.message}</span> : null}
            </div>

            {statusMessage ? <p className="form-status">{statusMessage}</p> : null}
            <button className="submit-btn" type="submit">
              Send Message
            </button>
          </form>
        </div>

        <div className="contact-side">
          <div className="contact-info-grid">
            {contactMethods.map((method) => (
              <InfoCard
                key={method.title}
                title={method.title}
                heading={method.heading}
                details={method.details}
              />
            ))}
          </div>
        </div>
      </div>

      <section className="faq-section">
        <SectionHeading
          title="Frequently Asked Questions"
          intro="Feedback from peers emphasized clearer answers around shipping, returns, and bidding, so those details are surfaced here."
        />
        {faqs.map((item, index) => (
          <FAQItem
            key={item.question}
            item={item}
            isOpen={openQuestion === index}
            onToggle={() => setOpenQuestion((current) => (current === index ? -1 : index))}
          />
        ))}
      </section>
    </section>
  );
}

export default ContactPage;
