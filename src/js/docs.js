/**
 * Documentor for XOOPS
 * Documentation system for XOOPS based on Common Utilities
 * 
 * Copyright © 2014 Eduardo Cortés
 * -----------------------------------------------------------------
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 * -----------------------------------------------------------------
 * @package      Documentor
 * @author       Eduardo Cortés <yo@eduardocortes.mx>
 * @copyright    2009 - 2014 Eduardo Cortés
 * @license      GPL v2
 * @link         http://eduardocortes.mx
 * @link         http://xoopsmexico.net
 */

var contentNavigator = {

    navigate: function( url, pushState ){

        if ( url == '' )
            return;

        $.get( url, {hideIndex:1}, function( response ){

            if ( response.type == 'error' ){

                $("#docs-resource-content").html('<div class="text-center"><span class="label label-danger">' + response.message + '</span></div>');
                return false;

            }

            if (pushState)
                window.history.pushState( {}, '', url );

            $("html title").html(response.title);

            $("#docs-resource-content").html( response.content );
            contentNavigator.navigation( response.id );

            $("#docs-resource-index a").trigger('update');

        }, 'json');

        return false;
    },

    navigation: function( id ){

        var index = $("#docs-resource-index ul li");
        var total = index.length;
        var prev, next;
        var found = false;

        for( i = 0; i<total; i++){

            if ( found ){
                next = $(index[i]).find("a").attr("href");
                break;
            }

            if ( $(index[i]).data('section') == id )
                found = true;
            else{
                prev = $(index[i]).find('a').attr("href");
            }

        }

        if ( prev != undefined )
            $(".docs-content-article .previous").attr('href', prev);
        else
            $(".docs-content-article .previous").hide();

        if ( next != undefined )
            $(".docs-content-article .next").attr('href', next);
        else
            $(".docs-content-article .next").hide();

    }

}

$(document).ready(function(){

    /*         CONTENT NAVIGATION         */
    /* ================================== */
    window.onpopstate = function( e ){
        contentNavigator.navigate( window.location.href, false );
    }

    $('body').on('click', "#docs-resource-index .docs-index a, .docs-content-inner a, .docs-content-article .document-navigation a", function(){

        if ( $(this).attr('target') == '_blank' )
            return;

        if ( $(this).attr('rel') == 'external' ){
            $(this).attr('target', '_blank');
            return;
        }

        if ( -1 >= $(this).attr("href").indexOf(docsUrl) ){
            $(this).attr('target', '_blank');
            return;
        }

        contentNavigator.navigate( $(this).attr("href"), true );
        return false;

    } );

    // Load default section
    contentNavigator.navigate( window.location.href, false );

    $(".note-link").click(function(){
        
        var id = $(this).attr('href').replace("#note-",'');
        $("#note-"+id).effect("highlight",{}, 5000);
        
    });

    // Dot dot dot
    if ( $("#docs-resource-index").length > 0 ){

        $("#docs-resource-index a").dotdotdot({
            watch: "window",
            wrap: 'letter'
        });
    }

    $("body").on('click', '.toggle-summary', function() {

        $("html").toggleClass("with-index");

        return false;

    });

    $("body").on('click', '.toggle-align', function(){

        var align = 'align-' + $(this).data('align');
        if($("html").hasClass(align))
            return;

        $("html").removeClass('align-left align-center align-justify').addClass(align);

        $.cookie('docu_align', $(this).data('align'),{
            expires: 30,
            path: '/'
        });

        return false;

    });

});