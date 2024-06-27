<?php
$title = "Homepage";
include 'MainHeader.php';
?>
<div class="headerbg">
    <div class="header-content">
        <p>Explore a world of knowledge with our extensive collection of books. Enjoy exclusive discounts and free shipping for members. Join now and start your reading journey!</p>
        <a class="btn btn-primary" href="MainLogin.php">Get Started</a>
    </div>
</div>
<div class="home-content">
    <div class="home-container">
        <h2>Quotes</h2>
        <div id="quotes" class="quotes-holder">
            <!-- Quotes will be injected here by JavaScript -->
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const quotes = [{
                text: "The more that you read, the more things you will know. The more that you learn, the more places you'll go.",
                author: "Dr. Seuss"
            },
            {
                text: "A reader lives a thousand lives before he dies. The man who never reads lives only one.",
                author: "George R.R. Martin"
            },
            {
                text: "Books are a uniquely portable magic.",
                author: "Stephen King"
            },
            {
                text: "There is no friend as loyal as a book.",
                author: "Ernest Hemingway"
            },
            {
                text: "A room without books is like a body without a soul.",
                author: "Marcus Tullius Cicero"
            },
            {
                text: "Reading is essential for those who seek to rise above the ordinary.",
                author: "Jim Rohn"
            },
            {
                text: "I have always imagined that Paradise will be a kind of library.",
                author: "Jorge Luis Borges"
            },
            {
                text: "The only thing that you absolutely have to know, is the location of the library.",
                author: "Albert Einstein"
            }
        ];

        let currentIndex = 0;
        const quotesContainer = document.getElementById('quotes');

        function displayQuotes() {
            quotesContainer.innerHTML = '';
            for (let i = 0; i < 3; i++) {
                const quote = quotes[(currentIndex + i) % quotes.length];
                const quoteCard = document.createElement('div');
                quoteCard.className = 'quote-card';
                quoteCard.innerHTML = `
                    <p class="quote-text">"${quote.text}"</p>
                    <p class="quote-author">- ${quote.author}</p>
                `;
                quotesContainer.appendChild(quoteCard);
            }
            currentIndex = (currentIndex + 3) % quotes.length;
        }

        displayQuotes();
        setInterval(displayQuotes, 5000); // Change quotes every 5 seconds
    });
</script>
</body>

</html>