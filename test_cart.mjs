import { chromium } from 'playwright';

(async () => {
  const browser = await chromium.launch();
  const context = await browser.createBrowserContext();
  const page = await context.newPage();

  try {
    // Navigate to shop page
    console.log('📱 Navigating to shop...');
    await page.goto('http://snackbox-app.test:8000/shop', { waitUntil: 'networkidle' });
    
    await page.waitForTimeout(2000);
    
    // Check if we're on shop page
    const title = await page.title();
    console.log(`✓ Page title: ${title}`);
    
    // Take screenshot of shop
    await page.screenshot({ path: 'shop-before.png' });
    console.log('✓ Screenshot: shop-before.png');
    
  } catch (error) {
    console.error('❌ Error:', error.message);
  } finally {
    await browser.close();
  }
})();
