# Quick Setup Guide

## ✅ Assets Built Successfully!

The Tailwind CSS has been compiled and the design should now be visible.

## 🔧 Fix PowerShell Execution Policy (Optional)

To run `npm` commands directly in PowerShell, run this command as Administrator:

```powershell
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
```

Or use `cmd /c` prefix for npm commands:
```bash
cmd /c npm run dev
cmd /c npm run build
```

## 🚀 Quick Start

### 1. Start the Server

The server should already be running at: `http://127.0.0.1:8000`

### 2. Access Points

- **Admin Panel**: http://127.0.0.1:8000/admin
  - Email: `admin@phonebd.net`
  - Password: `password`

- **Discussion Pages** (examples):
  - http://127.0.0.1:8000/xiaomi-redmi-note-13
  - http://127.0.0.1:8000/samsung-galaxy-a54
  - http://127.0.0.1:8000/iphone-15-pro
  - http://127.0.0.1:8000/oneplus-12
  - http://127.0.0.1:8000/google-pixel-8

### 3. Test the Features

1. **Visit a discussion page** (e.g., xiaomi-redmi-note-13)
2. **Ask a question** without logging in (as a guest)
3. **Reply to existing discussions**
4. **Vote "Helpful"** on discussions
5. **Report spam** if needed
6. **Login to admin panel** to approve/reject pending content

## 🎨 Design Features

The visitor-facing pages now have:
- ✅ Modern Tailwind CSS design
- ✅ Mobile-first responsive layout
- ✅ Clean, minimal interface
- ✅ Smooth interactions with Alpine.js
- ✅ Professional typography
- ✅ Accessible color scheme

## 📝 Development Workflow

### For Development (with hot reload):
```bash
# Terminal 1: PHP Server
php artisan serve

# Terminal 2: Vite Dev Server (use cmd /c)
cmd /c npm run dev
```

### For Production Build:
```bash
cmd /c npm run build
```

## 🔄 If Styles Still Don't Show

1. **Clear browser cache** (Ctrl + Shift + R)
2. **Check browser console** for any errors
3. **Verify build files exist**:
   - `public/build/manifest.json`
   - `public/build/assets/app-*.css`
   - `public/build/assets/app-*.js`

## 🎯 Next Steps

1. ✅ Assets compiled - **DONE**
2. Test discussion pages
3. Configure social login (Google, Facebook)
4. Sync phones from `phonebd_main` database
5. Customize theme colors if needed

---

**All set! The design should now be visible. Refresh your browser to see the changes.**
