/**
 * This script requires jQuery to be included.
 */

// Declare element properties
const worklogCard = {
  selector: '.clickable.worklog-card',
  element: null,
  location: 'existing-log'
}

const worklogCardNew = {
  selector: '.clickable.worklog-card-new',
  element: null,
  location: 'new-log'
}

const worklogCardEdit = {
  selector: '.clickable.worklog-card-edit',
  element: null,
  location: 'existing-log-edit'
}

const jqObjects = [];

$(document).ready(function () {
    console.log('work-log script loaded')

  jqObjects.push(worklogCard)
  jqObjects.push(worklogCardEdit)
  jqObjects.push(worklogCardNew)

  jqObjects.map(jqObj => {
    // Init jQuery elements
    jqObj.element = $(jqObj.selector)

    // Register click events on jQuery elements
    jqObj.element.click(function() {
      window.location.href = 'localhost/pages/' + jqObj.location
    })
  })

})
