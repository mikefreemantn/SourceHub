# Contributing to SourceHub

Thank you for your interest in contributing to SourceHub! This document provides guidelines and information for contributors.

## 🤝 How to Contribute

### Reporting Issues
- **Search existing issues** before creating a new one
- **Use clear, descriptive titles** for bug reports and feature requests
- **Include detailed information**:
  - WordPress version
  - PHP version
  - Plugin version
  - Steps to reproduce (for bugs)
  - Expected vs actual behavior

### Feature Requests
- **Check existing feature requests** to avoid duplicates
- **Describe the use case** and why the feature would be valuable
- **Provide examples** of how the feature would work
- **Consider implementation complexity** and plugin scope

### Code Contributions

#### Getting Started
1. **Fork the repository** on GitHub
2. **Clone your fork** locally
3. **Create a feature branch** from `main`
4. **Make your changes** following our coding standards
5. **Test thoroughly** in both hub and spoke modes
6. **Submit a pull request** with clear description

#### Coding Standards

**PHP Standards:**
- Follow **WordPress Coding Standards**
- Use **proper DocBlocks** for all functions and classes
- **Sanitize all inputs** and escape all outputs
- **Use WordPress functions** instead of native PHP when available
- **Maintain backward compatibility** when possible

**JavaScript Standards:**
- Follow **WordPress JavaScript Coding Standards**
- Use **modern ES6+ syntax** where appropriate
- **Comment complex logic** clearly
- **Test in multiple browsers**

**CSS Standards:**
- Follow **WordPress CSS Coding Standards**
- Use **BEM methodology** for class naming
- **Mobile-first responsive design**
- **Consistent spacing and formatting**

#### File Structure
```
SourceHub/
├── admin/                 # Admin interface files
│   ├── css/              # Admin stylesheets
│   ├── js/               # Admin JavaScript
│   └── views/            # Admin template files
├── includes/             # Core plugin classes
├── languages/            # Translation files
└── assets/              # Public assets
```

#### Database Changes
- **Always provide migration scripts** for schema changes
- **Test with existing data** to ensure compatibility
- **Document all database changes** in pull request
- **Use WordPress database functions** (`$wpdb`, etc.)

#### Security Considerations
- **Validate and sanitize all inputs**
- **Use nonces for form submissions**
- **Check user capabilities** before sensitive operations
- **Escape all outputs** to prevent XSS
- **Use prepared statements** for database queries

## 🧪 Testing

### Local Development Setup
1. **WordPress local environment** (Local by Flywheel, XAMPP, etc.)
2. **Two WordPress installations** (hub and spoke)
3. **Yoast SEO plugin** installed on both sites
4. **Debug logging enabled** (`WP_DEBUG = true`)

### Testing Checklist
- [ ] **Hub mode functionality**
  - [ ] Connection creation and testing
  - [ ] Content syndication
  - [ ] AI rewriting (if configured)
  - [ ] Smart links processing
  
- [ ] **Spoke mode functionality**
  - [ ] API key generation
  - [ ] Content receiving and creation
  - [ ] Yoast SEO field application
  - [ ] Featured image handling

- [ ] **Cross-browser compatibility**
  - [ ] Chrome/Chromium
  - [ ] Firefox
  - [ ] Safari
  - [ ] Edge

- [ ] **Responsive design**
  - [ ] Desktop (1920px+)
  - [ ] Tablet (768px-1024px)
  - [ ] Mobile (320px-767px)

### Test Cases
- **Basic syndication** with various post types
- **AI rewriting** with different models and settings
- **Smart links** in content, titles, and excerpts
- **Error handling** with invalid connections
- **Large content** with multiple images and links
- **Update syndication** for existing posts

## 📝 Pull Request Process

### Before Submitting
1. **Update documentation** if needed
2. **Add/update tests** for new functionality
3. **Update CHANGELOG.md** with your changes
4. **Ensure all tests pass**
5. **Check code follows standards**

### Pull Request Template
```markdown
## Description
Brief description of changes made.

## Type of Change
- [ ] Bug fix
- [ ] New feature
- [ ] Breaking change
- [ ] Documentation update

## Testing
- [ ] Tested in hub mode
- [ ] Tested in spoke mode
- [ ] Tested with AI rewriting
- [ ] Tested smart links functionality
- [ ] Cross-browser testing completed

## Screenshots (if applicable)
Add screenshots for UI changes.

## Checklist
- [ ] Code follows WordPress coding standards
- [ ] Self-review completed
- [ ] Documentation updated
- [ ] CHANGELOG.md updated
```

## 🏷️ Versioning

We use **Semantic Versioning** (SemVer):
- **MAJOR** version for incompatible API changes
- **MINOR** version for backward-compatible functionality
- **PATCH** version for backward-compatible bug fixes

## 📄 License

By contributing to SourceHub, you agree that your contributions will be licensed under the same GPL v2+ license as the project.

## 🆘 Getting Help

- **GitHub Issues**: For bugs and feature requests
- **GitHub Discussions**: For questions and community support
- **Code Review**: All pull requests receive thorough review

## 🙏 Recognition

All contributors will be recognized in our README and release notes. Thank you for helping make SourceHub better!

---

**Happy Contributing! 🚀**
