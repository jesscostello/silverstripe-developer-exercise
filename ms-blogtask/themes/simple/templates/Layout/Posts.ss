<h2>$Title</h2>

<% if $Posts %>
    <ul>
        <% loop $Posts %>
            <li><h4><a href="$Link">$Title</a></h4></li>
        <% end_loop %>
    </ul>
<% else %>
    <p>Sorry, no posts can be found at this time.</p>
<% end_if %>
