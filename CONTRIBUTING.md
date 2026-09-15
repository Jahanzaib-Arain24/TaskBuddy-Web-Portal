# Contributing to TaskBuddy 🤝

Thank you for your interest in contributing to **TaskBuddy**! We welcome contributions, bug reports, and feature suggestions to help make the platform even better.

---

## 📋 Code of Conduct

- Be respectful, constructive, and collaborative.
- Provide clear context, steps to reproduce, and screenshots for any issues or PRs.
- Write clean, secure, and documented PHP/JS/CSS code.

---

## 🚀 How to Contribute

### 1. Fork and Clone
```bash
# 1. Fork the repository on GitHub
# 2. Clone your fork locally
git clone https://github.com/your-username/TaskBuddy.git
cd TaskBuddy
```

### 2. Create a Feature Branch
```bash
git checkout -b feature/your-feature-name
# or for bug fixes:
git checkout -b fix/bug-description
```

### 3. Local Development & Testing
- Ensure your changes follow the existing project conventions (PDO prepared statements, clean indentation, CSS tokens in `css/custom_premium.css`).
- Run the automated QA test suite before submitting:
```bash
php tests/run_all_tests.php
```
Ensure all **753/753 tests pass**.

### 4. Commit and Push
```bash
git add .
git commit -m "feat: descriptive summary of your changes"
git push origin feature/your-feature-name
```

### 5. Open a Pull Request (PR)
- Open a PR against the `main` branch.
- Include a summary of your changes, what problem it solves, and test results.

---

## 🐛 Reporting Bugs

If you find a bug:
1. Check the [GitHub Issues](https://github.com/your-username/TaskBuddy/issues) tab to ensure it hasn't already been reported.
2. Open a new issue with:
   - Clear description of the bug.
   - Steps to reproduce.
   - Expected vs actual behavior.
   - Device/Browser environment details.

---

<p align="center">
  Thank you for contributing to <strong>TaskBuddy</strong>!
</p>
