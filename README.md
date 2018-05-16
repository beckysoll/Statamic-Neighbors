# statamic-neighbors
A statamic 2.0 tag pair to turn those replicator and bard sets into friendly neighbors. Hidey ho!

Copy `Neighbors` folder into `/site/addons/`.

## Usage

When templating replicator and bard sets, I have often found myself wanting to be able to add some extra markup depending on what the previous or next set type is. Maybe  text sets that come after pull quote sets should get some extra space, or I want to wrap all consecutive video sets in an extra div.

To use, replace the normal tag pair with the neighbors tags (basically just add "neighbors:" before the field name in both the opening and closing tags). Inside, these tags are available:

- `{{ neighbors:next }}`
  - returns the next set `type`
  - if there is no next set, returns `null`
- `{{ neighbors:previous }}`
  - returns the previous set `type`
  - if there is no previous set, returns `null`

A `field` paramater can be added to these tags to retrieve a value other than `type`:

- `{{ neighbors:next field="section_title" }}`
  - returns the next set `section_title`
  - if there is no next set or the next set does not have a `section_title`, returns `null`


### If your data looks like this

```yaml
evergreen_terrace:
  - 
    type: mansion
    family: The Bushes
    roof_color: gray
  - 
    type: house
    family: The Flanders
    roof_color: purple
  - 
    type: house
    family: The Simpsons
    roof_color: brown
  - 
    type: house
    family: The Wiggums
    roof_color: brown
```

### Template

```html
<ul>
{{ neighbors:evergreen_terrace }}
<li>We are {{ family }}</li>
  <ul>
    <li>We live in a {{ type }} with a {{ roof_color }} roof.</li>
    {{ if !first }}
    <li>{{ neighbors:previous field="family" }} live in the {{ neighbors:previous }} before us.</li>
    {{ /if }}
    {{ if !last }}
    <li>{{ neighbors:next field="family" }} live in the {{ neighbors:next }} after us.</li>
    {{ /if }}
    {{ if ({neighbors:previous field="roof_color"} == roof_color) }}
    <li>The {{ neighbors:previous }} before us has the same colored roof.</li>
    {{ /if }}
    {{ if ({neighbors:next field="roof_color"} == roof_color) }}
    <li>The {{ neighbors:next }} after us has the same colored roof.</li>
    {{ /if }}
  </ul>
{{ /neighbors:evergreen_terrace }}
</ul>
```

### Outputs

<ul>
  <li>We are The Bushes
    <ul>
      <li>We live in a mansion with a gray roof.</li>
      <li>The Flanders live in the house after us.</li>
    </ul>
  </li>
  <li>We are The Flanders
    <ul>
      <li>We live in a house with a purple roof.</li>
      <li>The Bushes live in the mansion before us.</li>
      <li>The Simpsons live in the house after us.</li>
    </ul>
  </li>
  <li>We are The Simpsons
    <ul>
      <li>We live in a house with a brown roof.</li>
      <li>The Flanders live in the house before us.</li>
      <li>The Wiggums live in the house after us.</li>
      <li>The house after us has the same colored roof.</li>
    </ul>
  </li>
  <li>We are The Wiggums
    <ul>
      <li>We live in a house with a brown roof.</li>
      <li>The Simpsons live in the house before us.</li>
      <li>The house before us has the same colored roof.</li>
    </ul>
  </li>
</ul>

---

If you have any questions, issues, or any suggestion on how to make my code better, feel free to reach out.
