/*
	Slimbox v2.04 - The ultimate lightweight Lightbox clone for jQuery
	(c) 2007-2010 Christophe Beyls <http://www.digitalia.be>
	MIT-style license.
*/

/* Modified by Charly Le Prof (30 may 2012) charlyleprof2@gmail.com
- if vc == 0 vertical center for image
- if vc == 1 vertical center for image + caption
- if ir == 0 image source
- if ir == 1 image resize with wm = width max, hm = height max;
- Read file with parenthesis e.g.: totolitoto (1).jpg
- Right click for "save as"
- Hide focus border line on click with ie7
- Remove l=document.documentElement from function (w)
*/

/* Modified by Dric (14 oct 2012) cedric@driczone.net
- wm and hm are now 90% of screen size instead of a fixed size.
*/

(function (w) {
    var E = w(window),
        u, f, F = -1,
        n, x, D, v, y, L, r, m = !window.XMLHttpRequest,
        s = [],
        k = {},
        t = new Image(),
        J = new Image(),
				vc = 1,
				ir = 1,
				H, a, g, p, I, d, G, c, A, K;
    w(function () {
        w("body").append(w([H = w('<div id="lbOverlay" />')[0], a = w('<div id="lbCenter" />')[0], G = w('<div id="lbBottomContainer" />')[0]]).css("display", "none"));
        g = w('<div id="lbImage" />').appendTo(a).append(p = w('<div style="position: relative;" />').append([I = w('<a id="lbPrevLink" href="#" />').click(B)[0], d = w('<a id="lbNextLink" href="#" />').click(e)[0]])[0])[0];
        c = w('<div id="lbBottom" />').appendTo(G).append([w('<a id="lbCloseLink" href="#" />').add(H).click(C)[0], A = w('<div id="lbCaption" />')[0], K = w('<div id="lbNumber" />')[0], w('<div style="clear: both;" />')[0]])[0]
				 // Ajout Charly (image resize)
				im =  w('<img />').appendTo(g).css({position:"absolute",width:"100%",height:"100%",top:0,left:0,backgroundRepeat:"no-repeat"});
		});
    w.slimbox = function (O, N, M) {
        u = w.extend({
            loop: false,
            overlayOpacity: 0.8,
            overlayFadeDuration: 400,
            resizeDuration: 400,
            resizeEasing: "swing",
            initialWidth: 250,
            initialHeight: 250,
            imageFadeDuration: 400,
            captionAnimationDuration: 400,
            counterText: "{x}/{y}",
            closeKeys: [27, 88, 67],
            previousKeys: [37, 80],
            nextKeys: [39, 78]
        }, M);
				if (typeof O == "string") {
            O = [
                [O, N]
            ];
            N = 0
        }
				// Ajout Charly
				hm = E.height() * 0.90;
				wm = E.width() * 0.90; // image resize
				var zIndexNumber = 9999; // redefine z-order
				$('div').each(function() {
					$(this).css('zIndex', zIndexNumber);
					zIndexNumber += 10;
				});
				I.hideFocus = true; // hide focus border line on click with ie7
				d.hideFocus = true;
				// Fin ajout Charly

				y = E.scrollTop() + (E.height() / 2);
        L = u.initialWidth;
        r = u.initialHeight;
				w(a).css({
            top: Math.max(0, y - (r / 2)),
            width: L,
            height: r,
            marginLeft: -L / 2
        }).show();
				v = m || (H.currentStyle && (H.currentStyle.position != "fixed"));
        if (v) {
            H.style.position = "absolute"
        }
        w(H).css("opacity", u.overlayOpacity).fadeIn(u.overlayFadeDuration);
        z();
        j(1);
        f = O;
				u.loop = u.loop && (f.length > 1);
        return b(N)
    };
    w.fn.slimbox = function (M, P, O) {
        P = P ||
        function (Q) {
            return [Q.href, Q.getAttribute('tiptitle')]
        };
        O = O ||
        function () {
            return true
        };
        var N = this;
        return N.unbind("click").click(function () {
            var S = this,
                U = 0,
                T, Q = 0,
                R;
            T = w.grep(N, function (W, V) {
                return O.call(S, W, V)
            });
            for (R = T.length; Q < R; ++Q) {
                if (T[Q] == S) {
                    U = Q
                }
                T[Q] = P(T[Q], Q)
            }
            return w.slimbox(T, U, M)
        })
    };
    function z() {
        var N = E.scrollLeft(),
            M = E.width();
        w([a, G]).css("left", N + (M / 2));
				if (v) {
            w(H).css({
                left: N,
                top: E.scrollTop(),
                width: M,
                height: E.height()
            })
        }
    }
    function j(M) {
        if (M) {
            w("object").add(m ? "select" : "embed").each(function (O, P) {
                s[O] = [P, P.style.visibility];
                P.style.visibility = "hidden"
            })
        } else {
            w.each(s, function (O, P) {
                P[0].style.visibility = P[1]
            });
            s = []
        }
        var N = M ? "bind" : "unbind";
        E[N]("scroll resize", z);
        w(document)[N]("keydown", o)
    }
    function o(O) {
        var N = O.keyCode,
            M = w.inArray;
        return (M(N, u.closeKeys) >= 0) ? C() : (M(N, u.nextKeys) >= 0) ? e() : (M(N, u.previousKeys) >= 0) ? B() : false
    }
    function B() {
        return b(x)
    }
    function e() {
        return b(D)
    }
    function b(M) {
		    if (M >= 0) {
            F = M;
            n = f[F][0];
            x = (F || (u.loop ? f.length : 0)) - 1;
            D = ((F + 1) % f.length) || (u.loop ? 0 : -1);
            q();
            a.className = "lbLoading";
            k = new Image();
            k.onload = i;
            k.src = n
        }
        return false
    }
    function i() {

				// Ajout Charly (image resize)
				nW = k.width;
				nH = k.height;
				if(ir==1){
					if(k.width > wm || k.height > hm) {
						nH = k.width / k.height < wm / hm ? hm : wm * (k.height / k.width);
						nW = k.width / k.height < wm / hm ? hm * (k.width / k.height) : wm;
					}
				}
				w(im).attr("src", n);
				// Fin ajout Charly

				a.className = "";
				w(g).css({
				visibility: "hidden",
				display: ""
				});
				w(p).width(nW); // replace k.width by nW (image resize)
				w([p, I, d]).height(nH);// replace k.height by nH (image resize)
				w(A).html(f[F][1] || "");
				w(K).html((((f.length > 1) && u.counterText) || "").replace(/{x}/, F + 1).replace(/{y}/, f.length));
				if (x >= 0) {
					t.src = f[x][0]
				}
				if (D >= 0) {
					J.src = f[D][0]
				}
				L = g.offsetWidth;
				r = g.offsetHeight;
				var M = Math.max(0, y - (r / 2));

				// Ajout Charly (vertical center for image and caption)
				if(vc==1) w(G).css({display: "",	visibility: "hidden", width: L});
				M = M - (G.offsetHeight/2) + 1;
				// Fin ajout Charly

				if (a.offsetHeight != r) {
						w(a).animate({
						height: r,
						top: M
					}, u.resizeDuration, u.resizeEasing)
				}
				// Ajout Charly (vertical center for image and caption) top: M
				if (a.offsetWidth != L) {
						w(a).animate({
						width: L,
						marginLeft: -L / 2,
						top: M
					}, u.resizeDuration, u.resizeEasing)
				}
				w(a).queue(function () {
					w(G).css({
						width: L,
						top: M + r,
						marginLeft: -L / 2,
						visibility: "hidden",
						display: ""
					});
					w(g).css({
						display: "none",
						visibility: "",
						opacity: ""
					}).fadeIn(u.imageFadeDuration, h)
				})
    }
    function h() {
        if (x >= 0) {
            w(I).show()
        }
        if (D >= 0) {
            w(d).show()
        }
        w(c).css("marginTop", -c.offsetHeight).animate({
            marginTop: 0
        }, u.captionAnimationDuration);
        G.style.visibility = ""
    }
    function q() {
        k.onload = null;
        k.src = t.src = J.src = n;
        w([a, g, c]).stop(true);
        w([I, d, g, G]).hide()
    }
    function C() {
        if (F >= 0) {
            q();
            F = x = D = -1;
            w(a).hide();
            w(H).stop().fadeOut(u.overlayFadeDuration, j)
        }
        return false
    }
})(jQuery);

// AUTOLOAD CODE BLOCK (MAY BE CHANGED OR REMOVED)
if (!/android|iphone|ipod|series60|symbian|windows ce|blackberry/i.test(navigator.userAgent)) {
    jQuery(function ($) {
        $("a[rel^='lightbox']").slimbox({ /* Put custom options here */
        }, null, function (el) {
            return (this == el) || ((this.rel.length > 8) && (this.rel == el.rel));
        });
    });
}