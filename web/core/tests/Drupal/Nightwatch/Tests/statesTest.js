module.exports = {
  '@tags': ['core'],
  before(browser) {
    browser.drupalInstall().drupalInstallModule('form_test', true);
  },
  after(browser) {
    browser.drupalUninstall();
  },
  'Test form with state API': (browser) => {
    browser
      .drupalRelativeURL('/form-test/javascript-states-form')
      .waitForElementVisible('body', 1000)
      .waitForElementNotVisible('input[name="textfield"]', 1000)
      .assert.noDeprecationErrors();
  },
  'Test number trigger with spinner widget': (browser) => {
    browser
      .drupalRelativeURL('/form-test/javascript-states-form')
      .waitForElementVisible('body', 1000)
      .waitForElementNotVisible(
        '#edit-item-visible-when-number-trigger-filled-by-spinner',
        1000,
      )
      .execute(() => {
        // Emulate usage of the spinner browser widget on number inputs
        // on modern browsers.
        const numberTrigger = document.getElementById('edit-number-trigger');
        numberTrigger.value = 1;
        numberTrigger.dispatchEvent(new Event('change'));
      });

    browser.waitForElementVisible(
      '#edit-item-visible-when-number-trigger-filled-by-spinner',
      1000,
    );
  },
};
