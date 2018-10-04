
<% if $ActiveMiniGalleryImages %>
    <% if $MiniGalleryCols == 1 %>
        <div class="minigallery lightgallery">
            <% loop $ActiveMiniGalleryImages %>
                <div class="minigallery-item">
                    <a href="$Image.URL" class="minigallery-item lightbox" data-src="$Image.URL" data-sub-html="$Image.Caption.ATT" data-exthumbimage="$Image.Fill(96,76).Link"><img class="img-responsive" src="$Image.ScaleWidth(1140).URL" alt="$Image.AltText.ATT"></a>
                </div>
            <% end_loop %>
        </div>
    <% else_if $MiniGalleryCols == 2 %>
        <div class="row minigallery lightgallery">
            <% loop $ActiveMiniGalleryImages %>
                <div class="col-xs-6 minigallery-item" data-src="$Image.URL" data-sub-html="$Caption.ATT">
                    <a href="$Image.URL" class="minigallery-item lightbox" data-src="$Image.URL" data-sub-html="$Image.Caption.ATT" data-exthumbimage="$Image.Fill(96,76).Link"><img width="720" height="720" class="img-responsive" src="$Image.FocusFill(720,720).URL" alt="$Image.AltText.ATT"></a>
                </div>
            <% end_loop %>
        </div>
    <% else_if $MiniGalleryCols == 3 %>
        <div class="row minigallery lightgallery">
            <% loop $ActiveMiniGalleryImages %>
                <div class="col-xs-4 minigallery-item" data-src="$Image.URL" data-sub-html="$Caption.ATT">
                    <a href="$Image.URL" class="minigallery-item lightbox" data-src="$Image.URL" data-sub-html="$Image.Caption.ATT" data-exthumbimage="$Image.Fill(96,76).Link"><img width="720" height="720" class="img-responsive" src="$Image.FocusFill(720,720).URL" alt="$Image.AltText.ATT"></a>
                </div>
            <% end_loop %>
        </div>
    <% else %>
        <%-- Standard: 4 --%>
        <div class="row minigallery lightgallery">
            <% loop $ActiveMiniGalleryImages %>
                <div class="col-xs-3 minigallery-item" data-src="$Image.URL" data-sub-html="$Caption.ATT">
                    <a href="$Image.URL" class="minigallery-item lightbox" data-src="$Image.URL" data-sub-html="$Image.Caption.ATT" data-exthumbimage="$Image.Fill(96,76).Link"><img width="720" height="720" class="img-responsive" src="$Image.FocusFill(720,720).URL" alt="$Image.AltText.ATT"></a>
                </div>
            <% end_loop %>
        </div>
    <% end_if %>
<% end_if %>
