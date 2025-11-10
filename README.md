# React Certificate Component

A beautiful, customizable certificate component built with React. This component creates professional-looking certificates with elegant styling, decorative borders, and customizable content.

## Features

- ✨ Beautiful, professional certificate design
- 🎨 Elegant gold borders and decorative elements
- 📝 Fully customizable content (name, award text, description, signatures, date)
- 📱 Responsive design that works on all screen sizes
- 🖨️ Print-friendly styling
- ⚡ Easy to integrate into any React application

## Installation

1. Clone this repository:
```bash
git clone <repository-url>
cd sharable_certificate
```

2. Install dependencies:
```bash
npm install
```

3. Start the development server:
```bash
npm start
```

The application will open at [http://localhost:3000](http://localhost:3000)

## Usage

### Basic Usage

```jsx
import Certificate from './Certificate';

function App() {
  return (
    <Certificate
      recipientName="John Doe"
      awardText="For Outstanding Achievement"
      description="This certificate is presented in recognition of exceptional dedication."
      date="November 10, 2025"
      signature1Name="Director"
      signature2Name="President"
    />
  );
}
```

### Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `recipientName` | string | "John Doe" | Name of the certificate recipient |
| `awardText` | string | "For Outstanding Achievement" | Main award/achievement text |
| `description` | string | Standard text | Detailed description of the achievement |
| `date` | string | Current date | Date of certificate issuance |
| `signature1Name` | string | "Director" | First signature title/name |
| `signature2Name` | string | "President" | Second signature title/name |

## Customization

### Changing Colors

Edit `src/Certificate.css` to customize the color scheme. The main color variables are:

- Border color: `#c9a961` (Gold)
- Text color: `#2c3e50` (Dark Blue)
- Background: Linear gradient from white to cream

### Fonts

The component uses Google Fonts:
- **Great Vibes** - For the recipient name (elegant cursive)
- **Playfair Display** - For titles (classic serif)
- **Montserrat** - For body text (modern sans-serif)

You can change these in the CSS file.

## Building for Production

```bash
npm run build
```

This creates an optimized production build in the `build` folder.

## Printing Certificates

The component includes print-specific styles. Simply use your browser's print function (Ctrl+P / Cmd+P) to print the certificate.

## Browser Support

Works in all modern browsers:
- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)

## License

MIT License - feel free to use this in your projects!

## Contributing

Contributions are welcome! Feel free to submit issues or pull requests.

## Screenshots

The certificate features:
- Ornate corner decorations
- Double border with gold accents
- Elegant typography
- Professional seal/emblem
- Signature lines
- Customizable content fields

---

Made with ❤️ using React
