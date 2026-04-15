function InfoCard({ title, heading, details }) {
  return (
    <article className="info-card">
      <p className="info-card-label">{title}</p>
      <h3>{heading}</h3>
      <p>{details}</p>
    </article>
  );
}

export default InfoCard;
