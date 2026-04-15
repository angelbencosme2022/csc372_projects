function FAQItem({ item, isOpen, onToggle }) {
  return (
    <article className="faq-item">
      <button className="faq-question" type="button" onClick={onToggle} aria-expanded={isOpen}>
        <span>{item.question}</span>
        <span>{isOpen ? "−" : "+"}</span>
      </button>
      {isOpen ? <div className="faq-answer">{item.answer}</div> : null}
    </article>
  );
}

export default FAQItem;
