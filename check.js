const { Builder, By, Key, until } = require('selenium-webdriver');

async function testGoogle() {
  let driver = await new Builder().forBrowser('chrome').build();

  try {
    // Open Google
    await driver.get('https://www.google.com');

    // Check if consent popup appears
    try {
      let consentButton = await driver.wait(
        until.elementLocated(By.xpath("//button[contains(text(),'I agree') or contains(text(),'Accept all')]")),
        3000
      );
      await consentButton.click();
      console.log('Consent popup accepted.');
    } catch (err) {
      console.log('No consent popup appeared.');
    }

    // Wait until search box is visible
    await driver.wait(until.elementLocated(By.name('q')), 5000);

    // Type in the search box and press ENTER
    await driver.findElement(By.name('q')).sendKeys('Selenium WebDriver', Key.RETURN);

    // Wait for the search results to load
    await driver.wait(until.titleContains('Selenium WebDriver'), 5000);

    console.log('Test passed: Google search completed successfully.');
  } catch (err) {
    console.error('Test failed:', err);
  } finally {
    await driver.quit();
  }
}

testGoogle();
