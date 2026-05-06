import elmoHatImage from "../assets/products/elmo_hat.jpg";
import gamingHatImage from "../assets/products/gaming_hat.jpg";
import dragonBallHoodieImage from "../assets/products/dragon_ball_hoodie.jpg";
import scenicGraphicTeeImage from "../assets/products/scenic_graphic_tee.jpg";
import americanMadeTeeImage from "../assets/products/american_made_tee_v2.jpg";
import keurigMiniMateImage from "../assets/products/keurig_mini_mate_v2.jpg";
import vintageStorageChestImage from "../assets/products/vintage_storage_chest_v2.jpg";
import maskedPlushDogImage from "../assets/products/masked_plush_dog_v2.jpg";

export const products = [
  {
    id: 7,
    name: "Elmo Lime Dad Cap",
    description:
      "Bright green adjustable cap with an embroidered Elmo detail and easy everyday fit.",
    image: elmoHatImage,
    imageAlt: "Lime green Elmo baseball cap hanging on a store rack",
    imageLabel: "Playful Streetwear",
    buyPrice: 24,
    bidPrice: 16,
    category: "accessories",
    condition: "Clean structure with vivid color and only light secondhand wear.",
  },
  {
    id: 8,
    name: "Gaming Quote Beanie",
    description:
      "Black knit beanie with bold embroidered lettering and blue controller graphic.",
    image: gamingHatImage,
    imageAlt: "Black beanie with gaming quote embroidery and blue controller icon",
    imageLabel: "Cold Weather Pick",
    buyPrice: 22,
    bidPrice: 14,
    category: "accessories",
    condition: "Soft knit in very good condition with crisp embroidery.",
  },
  {
    id: 9,
    name: "Dragon Ball Graphic Hoodie",
    description:
      "Gray pullover hoodie featuring a large Dragon Ball graphic with Goku and Frieza.",
    image: dragonBallHoodieImage,
    imageAlt: "Gray Dragon Ball graphic hoodie hanging on a rack",
    imageLabel: "Anime Essential",
    buyPrice: 38,
    bidPrice: 26,
    category: "clothing",
    condition: "Graphic is bright and the fleece looks clean with light thrifted wear.",
  },
  {
    id: 10,
    name: "Scenic Photo Graphic Tee",
    description:
      "White vintage-style graphic tee with a large scenic printed photo on the front.",
    image: scenicGraphicTeeImage,
    imageAlt: "White scenic photo graphic t-shirt hanging on a rack",
    imageLabel: "Soft Graphic Tee",
    buyPrice: 30,
    bidPrice: 20,
    category: "clothing",
    condition: "Good preowned condition with a soft feel and only minor wear.",
  },
  {
    id: 11,
    name: "American Made Red Tee",
    description:
      "Bold red graphic t-shirt with large American Made lettering and flag detail.",
    image: americanMadeTeeImage,
    imageAlt: "Red American Made graphic t-shirt laid flat",
    imageLabel: "Statement Tee",
    buyPrice: 28,
    bidPrice: 18,
    category: "clothing",
    condition: "Color looks strong and the print appears clean with light wear.",
  },
  {
    id: 12,
    name: "Keurig K-Mini Mate",
    description:
      "Compact single-serve Keurig coffee maker in box, sized for small kitchens and dorm setups.",
    image: keurigMiniMateImage,
    imageAlt: "Boxed Keurig K-Mini Mate coffee maker beside a window",
    imageLabel: "Home Upgrade",
    buyPrice: 48,
    bidPrice: 34,
    category: "home",
    condition: "Boxed item with packaging present; condition should be checked in person before purchase.",
  },
  {
    id: 13,
    name: "Vintage Storage Chest",
    description:
      "Large wood-and-metal trunk with worn character, decorative hardware, and plenty of storage.",
    image: vintageStorageChestImage,
    imageAlt: "Vintage wood storage chest displayed on a furniture floor",
    imageLabel: "Statement Storage",
    buyPrice: 95,
    bidPrice: 70,
    category: "furniture",
    condition: "Noticeable age and surface wear that adds patina and vintage character.",
  },
  {
    id: 14,
    name: "Masked Plush Dog",
    description:
      "Soft plush dog toy with oversized eyes, floppy ears, and a playful black mask detail.",
    image: maskedPlushDogImage,
    imageAlt: "Masked plush dog toy hanging on a store rack",
    imageLabel: "Playful Find",
    buyPrice: 18,
    bidPrice: 12,
    category: "toys",
    condition: "Looks clean and gently used with soft fabric and bright colors.",
  },
];

export const values = [
  {
    title: "Sustainability",
    description:
      "We extend the life of quality pieces and make secondhand shopping feel intentional and stylish.",
  },
  {
    title: "Quality",
    description:
      "Each listing is curated for condition, character, and wearability before it reaches the site.",
  },
  {
    title: "Accessibility",
    description:
      "Buy-now pricing and bidding options help customers shop within different budgets.",
  },
  {
    title: "Transparency",
    description:
      "Descriptions, pricing, and condition notes are clear so customers know what to expect.",
  },
  {
    title: "Community",
    description:
      "The shop is built for vintage lovers, sustainable shoppers, and anyone chasing one-of-a-kind finds.",
  },
];

export const contactMethods = [
  {
    title: "Email",
    heading: "hello@401thrift.com",
    details: "We typically respond within 24 hours.",
  },
  {
    title: "Social Media",
    heading: "@401thrift",
    details: "Follow us for new drops, styling ideas, and pop-up announcements.",
  },
  {
    title: "Response Time",
    heading: "Monday to Friday",
    details: "9:00 AM to 6:00 PM EST. Weekend messages are answered on Monday.",
  },
];

export const faqs = [
  {
    question: "How long does shipping take?",
    answer:
      "Orders usually ship within 1 to 2 business days, and delivery typically takes 3 to 5 business days.",
  },
  {
    question: "What if an item does not fit?",
    answer:
      "Returns are accepted within 7 days of delivery as long as the item is unworn and in original condition.",
  },
  {
    question: "How does bidding work?",
    answer:
      "Place one bid per item, track the current bid price, and use Buy Now if you do not want to wait for the auction window to end.",
  },
  {
    question: "Do you buy vintage items?",
    answer:
      "Yes. Customers can contact us with photos and details if they are interested in selling pieces to the shop.",
  },
  {
    question: "Are the items authentic?",
    answer:
      "Branded items are authenticated before listing, and anything we cannot verify does not get posted for sale.",
  },
];

export const navLinks = [
  { to: "/", label: "Home" },
  { to: "/shop", label: "Shop" },
  { to: "/about", label: "About" },
  { to: "/contact", label: "Contact" },
  { to: "/cart", label: "Cart" },
];
