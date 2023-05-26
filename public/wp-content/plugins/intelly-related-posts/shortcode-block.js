var el = wp.element.createElement;

const irpIcon = el(
  'svg',
    {height: "24", width: "35"},
    el(
      'text',
      {x: '0', y: '15', fill: 'red'},
      "[irp]"
    )
);

wp.blocks.registerBlockType("data443/irp-shortcode", {
  title: "Inline Related Posts", // Block name visible to the user within the editor
  icon: irpIcon, // Toolbar icon displayed beneath the name of the block
  category: "data443-category", // The category under which the block will appear in the Add block menu
  attributes: {
    // The data this block will be storing
    type: {type: "string", default: "default"},
    shortcode: {type: "string"}, 
  },
  edit: function (props) {
    // Defines how the block will render in the editor

    function updateShortcode(event) {
      props.setAttributes({shortcode: event.target.value});
    }

    function updateType(newdata) {
      props.setAttributes({type: newdata.target.value});
      var shortcode = "[irp]";
      if (newdata.target.value != shortcode) {
        shortcode = '[irp posts="' + newdata.target.value +'"]'; 
      }
      props.setAttributes({shortcode: shortcode});
    }

    if (props.attributes.shortcode === "") {
      props.attributes.shortcode = "[irp]";
    }

    var data = {
      'action' : 'irp_list_posts',
      'irp_post_type' : 'post'
    };

    jQuery.post(ajaxurl, data, function(response) {
      Object.keys(response['items']).forEach(key => {
        var data = response['items'][key];
        var postSelect = document.getElementById("irp-post-select");
        postSelect.add(new Option(data.text, data.id));
      });
    }, "json");

    return el(
      "div",
      {
        className: "irp-shortcode-edit",
        style: {'border' : '2px black solid', 'border-color': 'black', 'border-width': '2px', 'border-radius' : '5px', 'padding' : '10px'},
      },
      el("h3", null, "Inline Related Posts"),
      el("p", null,
        el("i", null, "tips: Select 'Inline Related Posts' to find related posts based on your link settings, or choose a specific post. The shortcode displayed in the textbox and my be edited.")
      ),
      el(
        "select",
        {
          id: "irp-post-select",
          onChange: updateType,
          value: props.attributes.type,
        },
        el("option", {value: "[irp]" }, "Inline Related Posts")
      ),
      el("input", {
        type: "text",
        placeholder: "[irp]",
        value: props.attributes.shortcode,
        onChange: updateShortcode,
        style: {width: "100%"},
      })
    ); // End return
  }, // End edit()

  save: function (props) {
    // Defines how the block will render on the frontend
    return el(
      "div",
      {
        className: "irp-shortcode",
      },
      props.attributes.shortcode
    );
  }
});
