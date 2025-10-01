# SourceHub Smart Links Shortcode Test

## Test Content for Classic Editor

Copy and paste this content into a Classic Editor post to test the shortcodes:

---

**Regular Smart Link Test:**

Check out our [smart-link path="/contact"]Contact Page[/smart-link] for more information.

Visit our [smart-link path="/services"]Services[/smart-link] to see what we offer.

**Custom Smart Link Test:**

Learn more about our [custom-smart-link urls='{"Spoke One":"http://spoke-1.local/special-page","Spoke Two":"http://spoke-2.local/different-page"}']Special Offers[/custom-smart-link].

**Mixed Content Test:**

Here's a paragraph with both types of links. First, visit our [smart-link path="/about"]About Us[/smart-link] page to learn about our company. Then check out our [custom-smart-link urls='{"Spoke One":"http://spoke-1.local/portfolio","Spoke Two":"http://spoke-2.local/work"}']Portfolio[/custom-smart-link] to see our work.

---

## Expected Results After Syndication:

The shortcodes should be converted to spans like:

```html
<span class="sourcehub-smart-link" data-smart-url="/contact">🔗 Contact Page</span>
<span class="sourcehub-custom-smart-link" data-custom-urls="{&quot;Spoke One&quot;:&quot;http://spoke-1.local/special-page&quot;,&quot;Spoke Two&quot;:&quot;http://spoke-2.local/different-page&quot;}">🌐 Special Offers</span>
```

And then processed by the Smart Links system to create proper links on each spoke site.
