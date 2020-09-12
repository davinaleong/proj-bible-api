$(document).ready(function() {
    console.log('edit-log-form loaded')

    for (let i = 0; i < 5; ++i) {
        const $tbody = $('#entry' + i + '-tbody')
        const $count = $('#entry' + i + '-count')
        const $addBtn = $('#entry' + i + '-add')

        let itemCount = Number($count.val())
        console.log('itemCount on load: ', itemCount, i)

        $addBtn.click(function() {
            itemCount = Number($count.val())
            console.log('itemCount when $addBtn is clicked: ', itemCount, i)

            $tbody.append(
                '<tr>\n' +
                '  <td>\n' +
                '    <sup class="text-success">NEW</sup>\n' +
                '    <input type="hidden" name="entries[' + i + '][items][' + itemCount + '][id]" value="new">\n' +
                '  </td>\n' +
                '  <td>\n' +
                '    <select name="entries[' + i + '][items][' + itemCount + '][code_project]"\n' +
                '        class="form-control form-control-sm" required>\n' +
                '      <option value="">- Select Project Code</option>\n' +
                '      <option value="KLICK" >Klick</option>\n' +
                '      <option value="LEARN" >Learning (tutorials, playground, etc)</option>\n' +
                '      <option value="OTHER" >Others</option>\n' +
                '      <option value="PROJ" >Personal Projects</option>\n' +
                '      <option value="POC" >Prototype or Proof-of-concept</option>\n' +
                '      <option value="SSO">Singapore Symphony Orchestra</option>\n' +
                '    </select>\n' +
                '  </td>\n' +
                '  <td>\n' +
                '    <input type="text" name="entries[' + i + '][items][' + itemCount + '][title_item]"\n' +
                '        class="form-control form-control-sm"\n required>\n' +
                '  </td>\n' +
                '  <td>\n' +
                '    <div class="form-check">\n' +
                '      <input class="form-check-input position-static" type="checkbox"\n' +
                '            name="entries[' + i + '][items][' + itemCount + '][remove]" value="remove">\n' +
                '    </div>\n' +
                '  </td>\n' +
                '</tr>'
            )

            itemCount += 1
            console.log('Added to itemCount: ', itemCount, i)
            $count.val(itemCount)
        })
    }
})
