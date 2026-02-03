<?php
// Template Name: Download Images


// Example: raw string of links separated by *
$links = "


";

// preg_match_all('/\*(https[^*]+?\.(?:png|jpg|jpeg|gif|webp))/i', $links, $matches);
preg_match_all('/https[^"\s]+?\.(?:png|jpg|jpeg|gif|webp)/i', $links, $matches);

$imageLinks = $matches[0];
?>


<div class="images">
    <?php foreach ($imageLinks as $index => $url): 
        $url = trim($url);
        if (empty($url)) continue;
        preg_match('/aem:([a-z0-9\-]+)/', $url, $idMatch);
        $uniqueId = $idMatch[1] ?? "image-" . ($index+1);
        $filename = "toyota-" . $uniqueId;
    ?>
        <a href="#" 
           class="downloadable-link" 
           data-src="<?php echo esc_url($url); ?>" 
           data-filename="<?php echo esc_attr($filename); ?>">
            Download <?php echo esc_html($filename); ?>
        </a>
    <?php endforeach; ?>
</div>

<!-- <div class="images">
    <?php 
        // foreach ($imageLinks as $index => $url): 
        // $url = trim($url);
        // if (empty($url)) continue;
        // preg_match('/aem:([a-z0-9\-]+)/', $url, $idMatch);
        // $uniqueId = $idMatch[1] ?? "image-" . ($index+1);
        // $filename = "toyota-" . $uniqueId;
        // $altText  = "Toyota " . $uniqueId;
    ?>
        <div class="image-wrapper">
            <img src="<?php //echo esc_url($url); ?>" 
                 class="downloadable" 
                 data-filename="<?php //echo esc_attr($filename); ?>" 
                 alt="<?php //echo esc_attr($altText); ?>">
        </div>
    <?php //endforeach; ?>
</div> -->


<style>
.images {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 50px;
}

.image-wrapper {
    width: 200px;
    height: 100px;
    border: 1px solid #c5c4c4ff;
}
.image-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    cursor: pointer;
}

.downloadable-link {
    display: inline-block;
    padding: 8px 14px;
    background-color: #0073e6;
    color: white;
    font-size: 14px;
    text-decoration: none;
    border-radius: 6px;
    transition: background-color 0.2s ease;
}

.downloadable-link:hover {
    background-color: #005bb5;
}
</style>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const links = Array.from(document.querySelectorAll(".downloadable-link"));
    let currentIndex = 0;
    let isRunning = false;
    let timer = null;

    const toggleBtn = document.createElement("button");
    toggleBtn.textContent = "Start Downloading";
    toggleBtn.style.cssText =
      "padding:10px 18px;background:#28a745;color:#fff;border:none;border-radius:6px;cursor:pointer;font-size:16px;position:fixed;top:10px;left:10px;z-index:9999;";
    document.body.appendChild(toggleBtn);

    const progressEl = document.createElement("div");
    progressEl.style.cssText =
      "position:fixed;top:10px;left:200px;background:#333;color:#fff;padding:8px 14px;border-radius:6px;font-size:14px;z-index:9999;";
    progressEl.textContent = `0 / ${links.length}`;
    document.body.appendChild(progressEl);

    const loaderEl = document.createElement("div");
    loaderEl.style.cssText =
      "position:fixed;bottom:20px;left:50%;transform:translateX(-50%);background:#000;color:#fff;padding:10px 20px;border-radius:6px;font-size:16px;z-index:9999;display:none;";
    loaderEl.textContent = "Downloading...";
    document.body.appendChild(loaderEl);

    function showLoader() {
      loaderEl.style.display = "block";
    }

    function hideLoader() {
      loaderEl.style.display = "none";
    }

    function updateProgress() {
      const done = currentIndex;
      const total = links.length;
      progressEl.textContent = `${done} / ${total}`;
    }

    function processLink(index) {
      if (!isRunning) return;
      if (index >= links.length) {
        toggleBtn.textContent = "Start Downloading";
        isRunning = false;
        hideLoader();
        return;
      }

      const linkEl = links[index];
      const img = new Image();
      img.crossOrigin = "anonymous";
      img.src = linkEl.getAttribute("data-src");

      const filename = linkEl.getAttribute("data-filename") || "image_" + (index + 1);

      showLoader();

      img.onload = function () {
        const canvas = document.createElement("canvas");
        canvas.width = img.width;
        canvas.height = img.height;

        const ctx = canvas.getContext("2d");
        ctx.drawImage(img, 0, 0);

        try {
          const webpData = canvas.toDataURL("image/webp");
          const a = document.createElement("a");
          a.href = webpData;
          a.download = filename + ".webp";
          a.click();
        } catch (error) {
          const a = document.createElement("a");
          a.href = img.src;
          a.download = filename + ".png";
          a.click();
        }

        linkEl.remove();
        currentIndex++;
        updateProgress();
        if (currentIndex >= links.length) hideLoader();
        timer = setTimeout(() => processLink(currentIndex), 1000);
      };

      img.onerror = () => {
        const a = document.createElement("a");
        a.href = linkEl.getAttribute("data-src");
        a.download = filename + ".png";
        a.click();

        linkEl.remove();
        currentIndex++;
        updateProgress();
        if (currentIndex >= links.length) hideLoader();
        timer = setTimeout(() => processLink(currentIndex), 1000);
      };
    }

    toggleBtn.addEventListener("click", function () {
      if (isRunning) {
        isRunning = false;
        clearTimeout(timer);
        toggleBtn.textContent = "Start Downloading";
        hideLoader();
      } else {
        isRunning = true;
        toggleBtn.textContent = "Stop Downloading";
        processLink(currentIndex);
      }
    });

    links.forEach(linkEl => {
      linkEl.addEventListener("click", function (e) {
        e.preventDefault();

        const img = new Image();
        img.crossOrigin = "anonymous";
        img.src = this.getAttribute("data-src");

        const filename = this.getAttribute("data-filename") || "manual_download";

        const linkWrapper = this;

        showLoader();

        img.onload = function () {
          const canvas = document.createElement("canvas");
          canvas.width = img.width;
          canvas.height = img.height;

          const ctx = canvas.getContext("2d");
          ctx.drawImage(img, 0, 0);

          try {
            const webpData = canvas.toDataURL("image/webp");
            const a = document.createElement("a");
            a.href = webpData;
            a.download = filename + ".webp";
            a.click();
          } catch (error) {
            const a = document.createElement("a");
            a.href = img.src;
            a.download = filename + ".png";
            a.click();
          }

          linkWrapper.remove();
          hideLoader();
          currentIndex++;
          updateProgress();
        };

        img.onerror = () => {
          const a = document.createElement("a");
          a.href = this.getAttribute("data-src");
          a.download = filename + ".png";
          a.click();
          linkWrapper.remove();
          hideLoader();
          currentIndex++;
          updateProgress();
        };
      });
    });

    updateProgress();
  });
</script>


<!-- <script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".downloadable-link").forEach(linkEl => {
            linkEl.addEventListener("click", function (e) {
                e.preventDefault();

                const img = new Image();
                img.crossOrigin = "anonymous";
                img.src = this.getAttribute("data-src");

                const filename = this.getAttribute("data-filename");
                const linkWrapper = this;

                img.onload = function () {
                    const canvas = document.createElement("canvas");
                    canvas.width = img.width;
                    canvas.height = img.height;

                    const ctx = canvas.getContext("2d");
                    ctx.drawImage(img, 0, 0);

                    try {
                        const webpData = canvas.toDataURL("image/webp");

                        const a = document.createElement("a");
                        a.href = webpData;
                        a.download = filename + ".webp";
                        a.click();
                    } catch (error) {
                        const a = document.createElement("a");
                        a.href = img.src;
                        a.download = filename + ".png";
                        a.click();
                    }
                    linkWrapper.remove();
                };

                img.onerror = () => {
                    const a = document.createElement("a");
                    a.href = this.getAttribute("data-src");
                    a.download = filename + ".png";
                    a.click();
                    linkWrapper.remove();
                };
            });
        });
    });
</script> -->


<!-- <script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".downloadable").forEach(imgEl => {
            imgEl.addEventListener("click", function () {
                const img = new Image();
                img.crossOrigin = "anonymous";
                img.src = this.src;
                const filename = this.getAttribute("data-filename");
                const wrapper = this.closest(".image-wrapper");

                img.onload = function () {
                    const canvas = document.createElement("canvas");
                    canvas.width = img.width;
                    canvas.height = img.height;

                    const ctx = canvas.getContext("2d");
                    ctx.drawImage(img, 0, 0);

                    try {
                        const webpData = canvas.toDataURL("image/webp");

                        const link = document.createElement("a");
                        link.href = webpData;
                        link.download = filename + ".webp"; 
                        link.click();
                    } catch (error) {
                        const link = document.createElement("a");
                        link.href = img.src;
                        link.download = filename + ".png"; 
                        link.click();
                    }

                    wrapper.remove();
                };

                img.onerror = () => {
                    const link = document.createElement("a");
                    link.href = this.src;
                    link.download = filename + ".png"; 
                    link.click();
                    wrapper.remove();
                };
            });
        });
    });
</script> -->